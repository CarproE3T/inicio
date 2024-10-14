<?php

function openDatabaseConnection() {
    $hostname = "localhost";
$username = "root";
$password = "e3te3te3t"; // Asegúrate de que esta contraseña sea correcta
$database = "carpro";
$port = 3306; // Verifica q


    $conn = new mysqli($hostname, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    return $conn;
}
