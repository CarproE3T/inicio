<?php
session_start();

// Limpiar y destruir la sesión
session_unset();  // Libera todas las variables de sesión
session_destroy(); // Destruye la sesión actual

// Redirigir al index.php después de cerrar la sesión
header("Location: index.php");
exit();
?>
