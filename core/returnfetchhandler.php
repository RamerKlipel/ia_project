<?php
namespace Core;

class ReturnFetchHandler {

    public static function sendSuccessFetch(array $arrData, int $httpCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($httpCode);
        return json_encode([
            'status' => 'success',
            'data' => $arrData
        ]);
    }

    public static function sendErrorFetch(string $message, int $httpCode = 500)
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($httpCode);
        return json_encode([
            'status' => 'error',
            'message' => $message,
        ]);
    }
}
