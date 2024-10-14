<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $proyectoId = $_POST['proyecto_id'];
    $nombreEstudiante = $_POST['nombre_estudiante'];
    $codigoEstudiante = $_POST['codigo_estudiante'];
    $correoEstudiante1 = $_POST['correo_estudiante1'];
    $correoEstudiante2 = isset($_POST['correo_estudiante2']) ? $_POST['correo_estudiante2'] : '';
    $celularEstudiante = isset($_POST['celular_estudiante']) ? $_POST['celular_estudiante'] : '';
    $nombreProyecto = $_POST['nombre_proyecto'];

    // Conexión a la base de datos
    $hostname = "localhost";
$username = "root";
$password = "e3te3te3t"; // Asegúrate de que esta contraseña sea correcta
$database = "carpro";
$port = 3306; // Verifica q

    $conn = new mysqli($hostname, $username, $password, $database, $port);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos: ' . $conn->connect_error]);
        exit();
    }

    // Obtener el correo del proponente basado en el ID del proyecto
    function obtenerCorreoProponente($proyectoId, $conn) {
        $sql = "SELECT email_profesor FROM proyectos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $proyectoId);
        $stmt->execute();
        $stmt->bind_result($correoProponente);
        $stmt->fetch();
        $stmt->close();
        return $correoProponente;
    }

    // Llamar a la función para obtener el correo del proponente
    $correoProponente = obtenerCorreoProponente($proyectoId, $conn);

    if (!$correoProponente) {
        echo json_encode(['success' => false, 'message' => 'Correo del proponente no encontrado.']);
        exit();
    }

    // Preparar los datos para enviar al Web Service
    $data = [
        'studentName' => $nombreEstudiante,
        'studentCode' => $codigoEstudiante,
        'studentEmail1' => $correoEstudiante1,
        'studentEmail2' => $correoEstudiante2,
        'studentPhone' => $celularEstudiante,
        'projectName' => $nombreProyecto,
        'proponentEmail' => $correoProponente
    ];

    // URL del Web Service de Google Apps Script
    $url = 'https://script.google.com/macros/s/AKfycbwXW8lC15muFx_9XXLTf3M_NmCISk3iUw0KyjslS7UQmDEgLnBW07KkcqXL3rmfRsNrMw/exec';

    // Enviar los datos al Web Service usando cURL
    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        echo json_encode(['success' => false, 'message' => 'Error al enviar el correo de interés.']);
    } else {
        $response = json_decode($result, true);
        if (isset($response['success']) && $response['success']) {
            // Registrar la selección en la base de datos
            $sql = "INSERT INTO selecciones (estudiante_id, proyecto_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $codigoEstudiante, $proyectoId);
            $stmt->execute();

            // Incrementar el contador de interesados en la tabla proyectos
            $sql = "UPDATE proyectos SET interesados = interesados + 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $proyectoId);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Correo enviado exitosamente y selección registrada.']);
        } else {
            $errorMsg = isset($response['error']) ? $response['error'] : 'Error desconocido.';
            echo json_encode(['success' => false, 'message' => $errorMsg]);
        }
    }

    $conn->close();
}
?>
