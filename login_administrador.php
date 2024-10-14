<?php
session_start();

// Verificar si el formulario de login ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Credenciales de administrador
    $admin_usuario = "carproE3T";
    $admin_contrasena = "e3te3te3t";

    // Verificar las credenciales
    if ($usuario === $admin_usuario && $contrasena === $admin_contrasena) {
        $_SESSION['administrador'] = true;
        header("Location: gestionar_proyectos_admin.php");
        exit();
    } else {
        $mensajeError = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
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
        input[type="text"], input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            width: 300px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Login Administrador</h1>
    <form method="post">
        <?php
        if (isset($mensajeError)) {
            echo "<p class='error'>$mensajeError</p>";
        }
        ?>
        <input type="text" name="usuario" placeholder="Usuario" required><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br>
        <input type="submit" value="Iniciar Sesión" class="boton">
    </form>
</body>
</html>
