<?php
session_start();
include 'webservice.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ordentipo = "leerautenticar";  // Usar "leerautenticar" para obtener todos los datos
    $id = $_POST["id"];
    
    $resultado = consumirWebService($ordentipo, $id);

    if (isset($resultado["resultado"]) && $resultado["resultado"] == "Exitoso") {
        // Almacenar todos los datos en la sesión
        $_SESSION['estudiante_id'] = $resultado["codigo"];
        $_SESSION['codigo_verificacion'] = $resultado['clave'];
        $_SESSION['nombre_estudiante'] = $resultado["nombre"];
        $_SESSION['correo_personal'] = $resultado["correoGmail"];
        $_SESSION['correo_institucional'] = $resultado["correos"];  // Si el segundo correo está en una lista o string
        $_SESSION['celular'] = $resultado["Celular"];
        
        header("Location: verificar_codigo.php");
        exit();
    } else {
        echo "Autenticación fallida: " . json_encode($resultado);
    }
}






