<?php

function esProyectoSeleccionado($estudiante_id, $proyecto_id, $conn) {
    $sql = "SELECT * FROM selecciones WHERE estudiante_id = ? AND proyecto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $estudiante_id, $proyecto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function esNovedadLeida($estudiante_id, $proyecto_id, $conn) {
    $sql = "SELECT * FROM novedades_leidas WHERE estudiante_id = ? AND proyecto_id = ? AND leido = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $estudiante_id, $proyecto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function marcarNovedadComoLeida($estudiante_id, $proyecto_id, $conn) {
    $sql = "INSERT INTO novedades_leidas (estudiante_id, proyecto_id, leido) VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE leido = 1, fecha_leido = CURRENT_TIMESTAMP";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $estudiante_id, $proyecto_id);
    $stmt->execute();
}
