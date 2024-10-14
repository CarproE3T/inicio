<?php
session_start();

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén el código ingresado por el usuario
    $codigo_ingresado = $_POST["codigo"];

    // Verifica si el código de verificación está almacenado en la sesión
    if (isset($_SESSION['codigo_verificacion'])) {
        $codigo_correcto = $_SESSION['codigo_verificacion'];  // Código almacenado en la sesión

        // Compara el código ingresado con el código almacenado
        if ($codigo_ingresado === $codigo_correcto) {
            // Si el código es correcto, redirige al usuario a la página de estudiante
            header("Location: estudiante.php");
            exit();
        } else {
            // Si el código es incorrecto, muestra un mensaje de error
            $error = "Código incorrecto. Inténtalo de nuevo.";
        }
    } else {
        // Si no hay código almacenado en la sesión, muestra un mensaje de error
        $error = "No hay un código de verificación generado. Inténtalo nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        input[type="text"] {
            width: calc(100% - 30px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: inset 0px 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.3);
        }

        .boton {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .boton:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .mensaje-error {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verificación de Código</h1>
        <form method="post" action="verificar_codigo.php">
        <p>Hemos enviado un código de verificación a su correo electronico, por favor digítelo a continuación para ingresar </p>
            <input type="text" name="codigo" placeholder="Ingrese el código de verificación" required>
            <input type="submit" value="Verificar" class="boton">
        </form>
        <?php if (isset($error)): ?>
            <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
