<?php
session_start();

// Verificar si el estudiante está autenticado
if (!isset($_SESSION['estudiante_id'])) {
    echo json_encode(['success' => false, 'message' => 'No has iniciado sesión.']);
    exit();
}

// Procesar los datos recibidos del cliente
$data = json_decode(file_get_contents('php://input'), true);
$proyecto_id = $data['proyecto_id'];
$nombre_estudiante = $data['nombre_estudiante'];
$codigo_estudiante = $_SESSION['estudiante_id']; // Obtenido de la sesión
$correo_estudiante1 = $data['correo_estudiante1'];
$correo_estudiante2 = isset($data['correo_estudiante2']) ? $data['correo_estudiante2'] : null;
$celular_estudiante = isset($data['celular_estudiante']) ? $data['celular_estudiante'] : null;
$nombre_proyecto = $data['nombre_proyecto'];

// Conexión a la base de datos
$hostname = "localhost";
$username = "root";
$password = "e3te3te3t"; // Asegúrate de que esta contraseña sea correcta
$database = "carpro";
$port = 3306; // Verifica q

// Crear conexión
$conn = new mysqli($hostname, $username, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error al conectar con la base de datos: ' . $conn->connect_error]);
    exit();
}

// Verificar si el estudiante ya ha seleccionado el proyecto
function esProyectoSeleccionado($estudiante_id, $proyecto_id, $conn) {
    $sql = "SELECT * FROM selecciones WHERE estudiante_id = ? AND proyecto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $estudiante_id, $proyecto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

if (!esProyectoSeleccionado($codigo_estudiante, $proyecto_id, $conn)) {
    // Insertar la selección en la base de datos
    $sql = "INSERT INTO selecciones (estudiante_id, proyecto_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $codigo_estudiante, $proyecto_id);
    
    if ($stmt->execute()) {
        // Incrementar el contador de interesados
        $sql_update = "UPDATE proyectos SET interesados = interesados + 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $proyecto_id);
        $stmt_update->execute();

        // Procesar el envío de correo (aquí puedes usar el WebService para enviar el correo)
        // Reemplaza esta sección con la lógica para enviar el correo si lo estás usando:
        // Ejemplo de integración con el WebService para el envío de correos:
        // ... Código para consumir WebService correo ...

        echo json_encode(['success' => true, 'message' => 'Interés registrado y correo enviado al proponente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar el interés: ' . $stmt->error]);
    }
} else {
    echo json_encode(['success' => true, 'message' => 'Ya has registrado tu interés en este proyecto.']);
}

$conn->close();
?>
