<?php
function obtenerSemestre() {
    $base_url = "https://script.google.com/macros/s/AKfycbxbd9Ldz9wa6RgseYzbn3nHsxH97mCMilv07Y1NynGm_TwnpBEfa9oAHAWu1pirrtqP/exec";
    $url = $base_url . "?orden=" . urlencode("IdSemestre");

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    curl_close($ch);

    // Si hubo un error
    if (isset($error_msg)) {
        return "Error: " . $error_msg;
    }

    // Decodificar la respuesta JSON
    $json_response = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return "Error: " . json_last_error_msg();
    }

    return $json_response['semestre'] ?? "Semestre no disponible";
}
?>
