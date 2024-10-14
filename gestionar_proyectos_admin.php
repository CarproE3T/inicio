<?php
session_start();

// Prevenir el almacenamiento en caché de las páginas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verificar si el administrador está autenticado
if (!isset($_SESSION['administrador'])) {
    header("Location: login_administrador.php");
    exit();
}

// Variables de conexión a la base de datos
$hostname = "localhost";
$username = "root";
$password = "e3te3te3t"; // Asegúrate de que esta contraseña sea correcta
$database = "carpro";
$port = 3306; // Verifica q

// Conexión a la base de datos
$conn = new mysqli($hostname, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

function mostrarOpciones() {
    echo "<h1>Opciones del Administrador</h1>";
    echo "<a href='?accion=crear' class='boton'>Crear propuesta</a>";
    echo "<a href='?accion=editar' class='boton'>Editar todas las propuestas</a>";
    echo "<a href='?accion=ver' class='boton'>Ver todas las propuestas</a>";
    echo "<a href='logout_admin.php' class='boton'>Cerrar Sesión</a>";
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
    echo "<a href='index.php' class='boton'>Cancelar</a>";
}

function mostrarEditarPropuesta() {
    global $conn;
    $sql = "SELECT id, descripcion FROM proyectos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Editar todas las Propuestas</h1>";
        echo "<ul class='lista-propuestas'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><a href='editar_admin.php?id=" . $row["id"] . "' class='boton'>" . htmlspecialchars($row["descripcion"]) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No hay propuestas disponibles para editar.";
    }
    echo "<a href='index.php' class='boton'>Cancelar</a>";
}

function mostrarVerDisponibilidad() {
    global $conn;
    $sql = "SELECT id, descripcion FROM proyectos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h1>Todas las Propuestas</h1>";
        echo "<table class='disponibilidad'>";
        echo "<tr><th>Descripción</th><th>Acción</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["descripcion"]) . "</td>";
            echo "<td><a href='editar_admin.php?id=" . $row["id"] . "' class='boton'>Editar</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay propuestas disponibles.";
    }
    echo "<a href='index.php' class='boton'>Volver a la Página de Inicio</a>";
}

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Opciones del Administrador</title>
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
            width: 100%; /* Ocupa toda la pantalla */
        }
        .lista-propuestas li {
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
        }
        .lista-propuestas a {
            color: white;
            text-decoration: none;
            background-color: #4CAF50;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease, transform 0.3s ease;
            display: block;
            width: 50%; /* Propuestas más amplias */
            font-size: 1.2em; /* Texto más grande */
            line-height: 1.6; /* Mejor espaciado de líneas */
            text-align: left;
            padding-left: 30px;
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
