<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_ingresado = $_POST["codigo"];
    if ($codigo_ingresado === $_SESSION['codigo_verificacion_proponente']) {
        header("Location: proponedor_de_proyectos.php");
        exit();
    } else {
        $error = "Código incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
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
        input[type="text"] {
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
    <h1>Verificación de Código</h1>
    <form method="post" action="verificar_codigo_proponente.php">
     <p>Hemos enviado un código de verificación a su correo electrónico, por favor digítelo a continuación para ingresar </p>
        <input type="text" name="codigo" placeholder="Ingrese el código de verificación" required>
        <input type="submit" value="Verificar" class="boton">
    </form>
    <?php if (isset($error)): ?>
        <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>


