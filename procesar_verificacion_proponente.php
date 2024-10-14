<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoIngresado = $_POST['codigo'];

    // Simulación de verificación del código correcto
    $codigoCorrecto = '390585'; // Esto debería venir de una fuente segura o un almacenamiento temporal

    if ($codigoIngresado === $codigoCorrecto) {
        // Autentica al proponente
        $_SESSION['proponente'] = true;
        // Opcional: guarda el email del proponente para utilizar más adelante
        $_SESSION['emailProponente'] = 'correo@ejemplo.com'; // Ajusta según el contexto real

        // Redirige al panel de gestión de proyectos
        header('Location: gestionar_proyectos.php');
        exit();
    } else {
        echo "<p style='color: red;'>Código incorrecto. Inténtalo de nuevo.</p>";
    }
}
?>
