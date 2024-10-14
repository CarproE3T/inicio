<?php
session_start();

// Verificar si el usuario es administrador o proponente
$isAdmin = isset($_SESSION['administrador']);
$isProponente = isset($_SESSION['proponenteCorreo']);

// Redirigir a la página de login si no está autenticado
if (!$isAdmin && !$isProponente) {
    header("Location: login_proponente.php"); // O puedes redirigir al login del administrador si prefieres
    exit();
}

// Conexión a la base de datos
$hostname = "localhost";
$username = "root";
$password = "e3te3te3t"; // Asegúrate de que esta contraseña sea correcta
$database = "carpro";
$port = 3306; // Verifica q

$conn = new mysqli($hostname, $username, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    // Procesar los datos del formulario
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $numeroEstudiantes = $conn->real_escape_string($_POST['numeroEstudiantes']);
    $emailProfesor = $conn->real_escape_string($_POST['emailProfesor']);
    $datosad = $conn->real_escape_string($_POST['datosad']);
    $fecha = $conn->real_escape_string($_POST['fecha']);
    
    // Insertar los datos en la tabla 'proyectos'
    $sql = "INSERT INTO proyectos (descripcion, numero_estudiantes, email_profesor, fecha, datosad, disponible) 
            VALUES ('$descripcion', '$numeroEstudiantes', '$emailProfesor', '$fecha', '$datosad', $disponible)";
    
    if ($conn->query($sql) === TRUE) {
        // Mostrar mensaje de éxito
        echo '<div class="message-container success">';
        echo '<h2>Propuesta guardada exitosamente.</h2>';
        echo '</div>';

        // Redirección automática después de 3 segundos a la página correspondiente
        if ($isAdmin) {
            echo '
            <script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "gestionar_proyectos_admin.php";
                }, 3000);
            </script>';
        } elseif ($isProponente) {
            echo '
            <script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "proponedor_de_proyectos.php";
                }, 3000);
            </script>';
        }
    } else {
        // Mostrar mensaje de error si no se pudo guardar la propuesta
        echo '<div class="message-container error">';
        echo '<h2>Error al guardar la propuesta: ' . $conn->error . '</h2>';
        echo '</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Registro de Propuesta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .message-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .message-container h2 {
            font-size: 24px;
            margin: 0;
            padding: 0;
        }

        .success h2 {
            color: #28a745; /* Verde para el éxito */
        }

        .error h2 {
            color: #dc3545; /* Rojo para los errores */
        }
    </style>
</head>
<body>
</body>
</html>
