<?php
namespace Core;

class ErrorHandler {
    public array $arrInfo;
    public static function setHandler(): void
    {
        set_exception_handler([self::class, "handlerException"]);

        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, $errno, 500, $errfile, $errline);
        });

        register_shutdown_function([self::class, 'handlerShutdown']);
    }

    public static function handlerException(\Throwable $e)
    {
        $code = (int)$e->getCode() ?? 500;
        http_response_code($code);
        if (ob_get_length()) {
            ob_clean();
        }

        // header('Content-Type: application/json; charset=utf-8');

        $arrInfo = [
            'status' => "error",
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];

        require_once __DIR__. "/../view/error.view.php";

        // echo json_encode($arrInfo);
        exit;
    }

    public static function handlerShutdown()
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $code = 500;
            http_response_code($code);

            if (ob_get_length()) {
                ob_clean();
            }

            header('Content-Type: application/json; charset=utf-8');

            $arrInfo = [
                'status' => "error",
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'trace' => $error['trace'] ?? "make some tests before changing something forever",
            ];

            require_once __DIR__. "/../view/error.view.php";
            exit;
        }
    }
}
