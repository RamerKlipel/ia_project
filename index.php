<?php
require_once './core/errorhandler.php';

use Core\ErrorHandler;

ErrorHandler::setHandler();

require_once './vendor/autoload.php';
require_once './core/functions.php';

if (file_exists('./config/control.php')) {
    require_once './config/control.php';
}

class index {
    public static function getController(): void
    {
        $class = $function = '';
        $params = $_GET;
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($url, '/'));
        $last = $parts[array_key_last($parts)];

        if (preg_match('/^([^@?]+)(?:@([^?]+))?/', $last, $matches)) {
            $class = $matches[1];
            $class = (explode('.', $class)[0] ?? $class);
            if (!empty($matches[2] ?? "")) {
                $function = $matches[2];
            }
        }

        // if (!in_array($class, getPagesPermissions()) || !file_exists("./controller/$class.php")) {
        //     callViewFrom('emptyindex');
        //     echo '<h1 style="margin: 0">Erro 404! Página não encontrada></h1>';die;
        // } else {
            try {
                require_once "./controller/ChatOllama.php";
                $nmClasse = "Controllers\\ChatOllama";
                $controller = new $nmClasse;

                if (!empty($function) && method_exists($controller, $function)) {
                    call_user_func_array([$controller, $function], array_values($params) ?: []);
                } else {
                    if (method_exists($controller, "render")) {
                        $controller->render();
                    }
                }
            } catch (\Exception $e) {
                throw $e;
            }
        // }
    }
}

index::getController();
