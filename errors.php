<?php

function badRequest($message)
{

    header('Content-Type: application/json');
    header("HTTP/1.1 400 Bad Request");
    $response = [
        'message' => $message
    ];
    echo json_encode($response);
    exit;
}
