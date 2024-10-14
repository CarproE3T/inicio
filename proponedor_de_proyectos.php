<?php
session_start();

// Verificar si el proponente está autenticado
if (!isset($_SESSION['proponenteCorreo'])) {
    header("Location: login_proponente.php");
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

function mostrarOpciones() {
    echo "<h1>Opciones del Proponente</h1>";
    echo "<a href='?accion=crear' class='boton'>Crear propuesta</a>";
    echo "<a href='?accion=editar' class='boton'>Editar tus propuestas</a>";
    echo "<a href='?accion=ver' class='boton'>Ver todas las propuestas</a>";
    echo "<a href='logout.php' class='boton'>Cerrar Sesión</a>";
}

function mostrarCrearPropuesta() {
    echo "<h1>Crear Propuesta</h1>";
    echo "<small>Llene la ficha</small><br>";
    echo "<form action='procesar_registro.php' method='POST'>";
    echo "<small>Ponga aquí toda la información que el estudiante debe conocer sobre la propuesta.</small><br>";
    echo "<textarea name='descripcion' placeholder='Descripción del proyecto' required></textarea><br>";
    
    echo "<input type='number' name='numeroEstudiantes' placeholder='Número de estudiantes' required><br>";
    echo "<input type='email' name='emailProfesor' placeholder='Email del profesor' required><br>";
    echo "<input type='hidden' name='fecha' value='" . date("Y-m-d") . "'>";
    echo "<label for='disponible'>Disponible:</label>";
    echo "<input type='checkbox' id='disponible' name='disponible' value='1' checked><br>";
    echo "<textarea name='datosad' placeholder='Datos de contacto adicional (Teléfono, Oficina en la universidad)' required></textarea><br>";
    
    echo "<input type='submit' value='Registrar' class='boton'>";
    echo "</form>";
    echo "<a href='proponedor_de_proyectos.php' class='boton'>Cancelar</a>";
}

function mostrarEditarPropuesta() {
    global $conn;
    $correo = $_SESSION['proponenteCorreo'];
    $sql = "SELECT id, descripcion FROM proyectos WHERE email_profesor = '$correo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Editar tus Propuestas</h1>";
        echo "<ul class='lista-propuestas'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='editar_propuesta.php?id=" . $row["id"] . "' class='boton'>" . htmlspecialchars($row["descripcion"]) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No tienes propuestas disponibles para editar.";
    }
    
    echo "<a href='proponedor_de_proyectos.php' class='boton'>Cancelar</a>";
}

function mostrarVerDisponibilidad() {
    global $conn;
    $sql = "SELECT id, descripcion, disponible FROM proyectos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Todas las Propuestas</h1>";
        echo "<table class='disponibilidad'>";
        echo "<tr><th>Descripción</th><th>Disponible</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
            echo "<td>" . ($row['disponible'] ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay propuestas disponibles.";
    }
    echo "<a href='proponedor_de_proyectos.php' class='boton'>Volver a la Página de Inicio</a>";
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Opciones del Proponente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f0f0f0;
        }
        .boton {
            background-color: green;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
            border: none;
            cursor: pointer;
            width: 150px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            font-size: 1em;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
            text-decoration: none; /* Elimina el subrayado */
        }
        .boton:hover {
            background-color: darkgreen;
            transform: translateY(-5px);
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="number"], textarea {
            margin: 10px 0;
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        textarea {
            height: 100px;
        }
        .lista-propuestas {
            list-style-type: none;
            padding: 0;
            text-align: center;
            display: inline-block;
        }
        .lista-propuestas li {
            margin-bottom: 10px;
        }
        .lista-propuestas a {
            color: white;
            text-decoration: none; /* Elimina el subrayado en los enlaces */
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: block;
        }
        .lista-propuestas a:hover {
            background-color: darkgreen;
            transform: translateY(-5px);
        }
        table.disponibilidad {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 80%;
        }
        table.disponibilidad th, table.disponibilidad td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table.disponibilidad th {
            background-color: #4CAF50;
            color: white;
        }
        table.disponibilidad tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table.disponibilidad tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <?php
    switch ($accion) {
        case 'crear':
            mostrarCrearPropuesta();
            break;
        case 'editar':
            mostrarEditarPropuesta();
            break;
        case 'ver':
            mostrarVerDisponibilidad();
            break;
        default:
            mostrarOpciones();
            break;
    }
    ?>
</body>
</html>
