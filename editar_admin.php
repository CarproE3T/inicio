<?php
session_start();

// Verificar si el proponente o administrador está autenticado
if (!isset($_SESSION['proponenteCorreo']) && !isset($_SESSION['administrador'])) {
    header("Location: login_administrador.php");
    exit();
}

// Conexión a la base de datos
$hostname = "localhost";
$username = "root";
$password = "e3te3te3t"; // Asegúrate de que esta contraseña sea correcta
$database = "carpro";
$port = 3306; // Verifica q

$conn = new mysqli($hostname, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del proyecto a editar desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id === null) {
    die("ID del proyecto no proporcionado.");
}

// Manejar la eliminación de la propuesta
if (isset($_POST['eliminar'])) {
    // Primero eliminamos todas las selecciones relacionadas con este proyecto
    $sql_selecciones = "DELETE FROM selecciones WHERE proyecto_id = $id";
    if ($conn->query($sql_selecciones) === TRUE) {
        // Luego eliminamos el proyecto de la tabla proyectos
        $sql_proyecto = "DELETE FROM proyectos WHERE id = $id";
        if ($conn->query($sql_proyecto) === TRUE) {
            echo "<script>alert('Propuesta eliminada con éxito.');</script>";
            // Redirigir a la página principal después de eliminar
            echo "<script>setTimeout(function(){ window.location.href = 'gestionar_proyectos_admin.php'; }, 1000);</script>";
            exit();
        } else {
            echo "Error al eliminar la propuesta: " . $conn->error;
        }
    } else {
        echo "Error al eliminar las selecciones relacionadas: " . $conn->error;
    }
}


// Recuperar la información del proyecto
$sql = "SELECT * FROM proyectos WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Proyecto no encontrado.");
}
$row = $result->fetch_assoc();

// Si el formulario ha sido enviado para editar la propuesta
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['eliminar'])) {
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $numeroEstudiantes = $conn->real_escape_string($_POST['numeroEstudiantes']);
    $emailProfesor = $conn->real_escape_string($_POST['emailProfesor']);
    $datosad = $conn->real_escape_string($_POST['datosad']);
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    // Actualizar la información del proyecto
    $sql = "UPDATE proyectos SET 
            descripcion = '$descripcion', 
            numero_estudiantes = '$numeroEstudiantes', 
            email_profesor = '$emailProfesor', 
            datosad = '$datosad', 
            disponible = $disponible 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Propuesta actualizada con éxito.";
        // Redirigir a la página principal después de 3 segundos
        echo "<script>setTimeout(function(){ window.location.href = 'gestionar_proyectos_admin.php'; }, 3000);</script>";
    } else {
        echo "Error al actualizar la propuesta: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Propuesta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f2f2f2;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .boton {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }
        .boton.eliminar {
            background-color: #FF4C4C;
        }
        .boton:hover {
            background-color: #45a049;
        }
        .boton.eliminar:hover {
            background-color: #FF0000;
        }
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input, .input-group textarea {
            width: calc(100% - 22px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .input-group textarea {
            height: 100px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Editar Propuesta</h1>
    <form action="editar_propuesta.php?id=<?php echo $id; ?>" method="post">
        <div class="input-group">
            <small>Llene la ficha</small><br>
            <label for="descripcion">Descripción:</label>
            <small>Ponga aquí toda la información que el estudiante debe conocer de la propuesta</small><br>
            <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($row["descripcion"]); ?></textarea>
        </div>
        <div class="input-group">
            <label for="numeroEstudiantes">Número de Estudiantes:</label>
            <input type="number" id="numeroEstudiantes" name="numeroEstudiantes" value="<?php echo htmlspecialchars($row["numero_estudiantes"]); ?>" required>
        </div>
        <div class="input-group">
            <label for="emailProfesor">Correo del Profesor:</label>
            <input type="email" id="emailProfesor" name="emailProfesor" value="<?php echo htmlspecialchars($row["email_profesor"]); ?>" required>
        </div>
        <div class="input-group">
            <label for="datosad">Datos adicionales de contacto:</label>
            <textarea id="datosad" name="datosad" required><?php echo htmlspecialchars($row["datosad"]); ?></textarea>
        </div>
        <div class="input-group">
            <label for="disponible">Disponible:</label>
            <input type="checkbox" id="disponible" name="disponible" <?php echo ($row['disponible'] ? "checked" : ""); ?>>
        </div>
        <input type="submit" value="Guardar Cambios" class="boton">
    </form>

    <!-- Botón de eliminar propuesta -->
    <form action="editar_admin.php?id=<?php echo $id; ?>" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta propuesta?');">
        <input type="hidden" name="eliminar" value="1">
        <input type="submit" value="Eliminar Propuesta" class="boton eliminar">
    </form>
</div>

</body>
</html>
