<?php
session_start(); // Inicia la sesión para almacenar el código
include 'webservice_proponentes.php'; // Asegúrate de que este archivo está correctamente incluido

// Habilita la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo']; // Obtener el correo del formulario

    try {
        // Llama al web service para enviar el código de seguridad
        $respuesta = consumir_webservice_proponentes($correo);

        if ($respuesta !== null && isset($respuesta['clave'])) {
            // Almacena el código de verificación en la sesión
            $_SESSION['codigo_verificacion'] = $respuesta['clave'];

            // Redirigir a la página de verificación de código
            header('Location: verificar_codigo_proponente.php');
            exit();
        } else {
            echo "<p style='color: red;'>Error al enviar el código. Inténtalo de nuevo.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>Excepción capturada: " . $e->getMessage() . "</p>";
    }
}
?>



