<?php
function consumirWebServiceProponente($ordentipo, $correo) {
    $base_url = "https://script.google.com/macros/s/AKfycbxv1t0PdwVkz-QbDx3026H8icq7ykAqN8ITMj_O4gv9moysv5-cTJHDxPpxsoGcd-eG/exec";
    $url = $base_url . "?ordentipo=" . urlencode($ordentipo) . "&correo=" . urlencode($correo);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    curl_close($ch);

    if (isset($error_msg)) {
        return array("error" => $error_msg);
    }

    // Log para depuración
    file_put_contents('response_log_proponentes.txt', $response);

    $json_response = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return array("error" => "JSON decode error: " . json_last_error_msg(), "response" => $response);
    }

    return $json_response;
}
