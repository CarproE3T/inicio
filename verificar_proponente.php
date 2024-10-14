<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ordentipo = $_POST["ordentipo"];
    $correo = $_POST["correo"];

    include 'webservice_proponentes.php'; // Asegúrate de que este archivo está en la ubicación correcta
    $resultado = consumirWebServiceProponente($ordentipo, $correo);

    // Verifica la respuesta del web service
    if (isset($resultado["resultado"]) && $resultado["resultado"] === "Exitoso") {
        $_SESSION['proponenteCorreo'] = $correo;
        $_SESSION['codigo_verificacion_proponente'] = $resultado['clave'];
        header("Location: verificar_codigo_proponente.php");
        exit();
    } else {
        $mensajeError = "Correo de proponente no válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación de Proponente</title>
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
        input[type="email"] {
            padding: 10px;
            margin: 10px 0;
            width: 300px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .mensaje-error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Autenticación de Proponente</h1>
    <form method="post" action="verificar_proponente.php">
        <input type="hidden" name="ordentipo" value="autenticar">
        <input type="email" name="correo" placeholder="Ingrese su correo electrónico" required>
        <input type="submit" value="Autenticar" class="boton">
    </form>
    <?php if (isset($mensajeError)): ?>
        <p class="mensaje-error"><?php echo htmlspecialchars($mensajeError); ?></p>
    <?php endif; ?>
</body>
</html>
