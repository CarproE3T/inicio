<?php
session_start();

if (!isset($_SESSION['estudiante_id'])) {
    header("Location: index.php");
    exit();
}

$estudiante_id = $_SESSION['estudiante_id'];
$nombre_estudiante = $_SESSION['nombre_estudiante'];
$correo_personal = $_SESSION['correo_personal'];
$correo_institucional = $_SESSION['correo_institucional'];
$celular = $_SESSION['celular'];

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

// Verificar si el estudiante ha seleccionado el proyecto
function esProyectoSeleccionado($estudiante_id, $proyecto_id, $conn) {
    $sql = "SELECT * FROM selecciones WHERE estudiante_id = ? AND proyecto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $estudiante_id, $proyecto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Obtener el término de búsqueda, si está definido
$busqueda = isset($_GET['busqueda']) ? $conn->real_escape_string($_GET['busqueda']) : '';

// Modificar la consulta para buscar coincidencias en varias columnas
$sql = "SELECT * FROM proyectos";
if (!empty($busqueda)) {
    $sql .= " WHERE descripcion LIKE '%$busqueda%' 
              OR numero_estudiantes LIKE '%$busqueda%'
              OR datosad LIKE '%$busqueda%'
              OR email_profesor LIKE '%$busqueda%'
              OR disponible LIKE '%$busqueda%'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Estudiante - Visualización de Propuestas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 50px;
            background-color: #f0f0f0;
        }
        h1 {
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        table {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 80%;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            white-space: nowrap;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .boton {
            background-color: green;
            color: white;
            padding: 10px 20px;
            margin: 20px 0;
            border: none;
            cursor: pointer;
            width: 200px;
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
        .boton.no-disponible {
            background-color: red;
            cursor: not-allowed;
        }
        .boton.seleccionado {
            background-color: yellow;
            color: black;
        }
    </style>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    // Pasar los datos de la sesión a JavaScript
    const estudianteData = {
        nombre: '<?php echo $nombre_estudiante; ?>',
        codigo: '<?php echo $estudiante_id; ?>',
        correo1: '<?php echo $correo_personal; ?>',
        correo2: '<?php echo $correo_institucional; ?>',
        celular: '<?php echo $celular; ?>'
    };

    function mostrarConfirmacion(event, proyectoId, nombreProyecto, boton) {
        event.preventDefault(); // Prevenir el envío del formulario

        Swal.fire({
            title: 'Advertencia',
            text: 'Una vez que envíes tu interés con tu nombre, código y correos, no podrás borrar esta información, y será enviada al correo del profesor. ¿Estás seguro de que deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Muchas gracias por haberse postulado',
                    html: 
                        `<p>Le hemos enviado su interés al proponente con los siguientes datos:</p>
                        <p><strong>Nombre:</strong> ${estudianteData.nombre}</p>
                        <p><strong>Código:</strong> ${estudianteData.codigo}</p>
                        <p><strong>Correo 1:</strong> ${estudianteData.correo1}</p>
                        <p><strong>Correo 2:</strong> ${estudianteData.correo2}</p>
                        <p><strong>Teléfono:</strong> ${estudianteData.celular}</p>`,
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Aquí se realiza el envío de los datos al servidor
                    const formData = new FormData();
                    formData.append('proyecto_id', proyectoId);
                    formData.append('nombre_estudiante', estudianteData.nombre);
                    formData.append('codigo_estudiante', estudianteData.codigo);
                    formData.append('correo_estudiante1', estudianteData.correo1);
                    formData.append('correo_estudiante2', estudianteData.correo2);
                    formData.append('celular_estudiante', estudianteData.celular);
                    formData.append('nombre_proyecto', nombreProyecto);

                    fetch('webservicecorreo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cambiar el botón a "Seleccionado"
                            boton.classList.remove('boton');
                            boton.classList.add('boton', 'seleccionado');
                            boton.innerText = 'Seleccionado';
                            boton.disabled = true; // Deshabilitar el botón
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo enviar el interés.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                    });
                });
            }
        });
    }
</script>

</head>
<body>
    <form method="get" action="">
        <input type="text" name="busqueda" placeholder="Buscar por palabra clave" value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>">
        <input type="submit" value="Buscar" class="boton">
    </form>

<?php
if ($result->num_rows > 0) {
    echo "<h1>Propuestas Disponibles</h1>";
    echo "<table>";
    echo "<tr><th>Descripción</th><th>Número de Estudiantes</th><th>Datos Adicionales</th><th>Disponibilidad</th><th>Interesados</th><th>Acción</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $descripcion = htmlspecialchars($row["descripcion"]);
        $numeroEstudiantes = htmlspecialchars($row["numero_estudiantes"]);
        $datosad = htmlspecialchars($row["datosad"]);
        $proyectoId = $row["id"];

        $seleccionado = esProyectoSeleccionado($estudiante_id, $proyectoId, $conn);
        $botonClase = $seleccionado ? 'boton seleccionado' : 'boton';
        $botonTexto = $seleccionado ? 'Seleccionado' : 'Estoy interesado';
        $botonDeshabilitado = $seleccionado ? 'disabled' : '';

        echo "<tr>";
        echo "<td>$descripcion</td>";
        echo "<td>$numeroEstudiantes</td>";
        echo "<td>$datosad</td>";
        echo "<td>" . ($row["disponible"] ? "Disponible" : "No Disponible") . "</td>";
        echo "<td>" . $row["interesados"] . "</td>";

        echo "<td>";
        if ($row["disponible"]) {
            echo "<form method='post' style='display:inline;' action='' onsubmit='mostrarConfirmacion(event, $proyectoId, \"$descripcion\", this.querySelector(\"button\"))'>
                    <input type='hidden' name='proyecto_id' value='$proyectoId'>
                    <button type='submit' class='$botonClase' $botonDeshabilitado>$botonTexto</button>
                  </form>";
        } else {
            echo "<button class='boton no-disponible' disabled>No Disponible</button>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay propuestas disponibles.</p>";
}

$conn->close();

echo "<a href='index.php' class='boton volver'>Volver a la Página de Inicio</a>";
?>
</body>
</html>
