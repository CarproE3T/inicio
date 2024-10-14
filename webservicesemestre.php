<?php
function obtenerSemestre() {
    $url = "https://script.google.com/macros/s/AKfycbxbd9Ldz9wa6RgseYzbn3nHsxH97mCMilv07Y1NynGm_TwnpBEfa9oAHAWu1pirrtqP/exec";
    $orden = "IdSemestre";
    
    // Realizar solicitud GET
    $response = file_get_contents($url . "?orden=" . $orden);
    $data = json_decode($response, true);
    
    return isset($data['IdSemestre']) ? $data['IdSemestre'] : "Semestre no disponible";
}
?>
