<?php
require_once './core/errorhandler.php';

use Core\ErrorHandler;

ErrorHandler::setHandler();

require_once './vendor/autoload.php';
require_once './core/functions.php';

if (file_exists('./config/control.php')) {
    require_once './config/control.php';
}

class index
{
    public static function getController()
    {
        $params = $_GET;

        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $arrUrlParts = explode('/', trim($url, '/'));
        $lastElmtUrl = $arrUrlParts[array_key_last($arrUrlParts)];

        [$nmClass, $nmExtension, $nmFunction] = self::extractClassExtensionFunction($lastElmtUrl);

        $arrPermExtension = ["css", "img", "js", "svg", "jpeg", "jpg", "png", "ico", "gif"];

        if (in_array(strtolower($nmExtension), $arrPermExtension)) {
            return false;
        }

        if ($nmExtension !== "php") {
            http_response_code(403);
            echo '<h1 style="margin: 0">Erro 403! Acesso negado.</h1>';
            die;
            return false;
        }

        if (!in_array($nmClass, getPagesPermissions()) || !file_exists("./controller/$nmClass.php")) {
            callViewFrom('emptyindex');
            echo '<h1 style="margin: 0">Erro 404! Página não encontrada></h1>';
            die;
        } else {
            try {
                require_once "./controller/ChatOllama.php";
                $nmClasse = "Controllers\\ChatOllama";
                $controller = new $nmClasse;


                if (!empty($nmFunction) && method_exists($controller, $nmFunction)) {
                    call_user_func_array([$controller, $nmFunction], array_values($params) ?: []);
                } else {
                    if (method_exists($controller, "render")) {
                        $controller->render();
                        $controller->main();
                    }
                }
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    private static function extractClassExtensionFunction(string $lastElmtUrl): array
    {
        $nmClass = $nmExtension = $nmFunction = "";

        if (preg_match('/^([^@?]+)(?:@([^?]+))?/', $lastElmtUrl, $matches)) {
            $nmBaseArq = $matches[1];

            $arrExplodeClass = explode('.', $nmBaseArq);

            $nmClass = ($arrExplodeClass[0] ?? $nmBaseArq);

            $nmExtension = ($arrExplodeClass[1] ?? null);

            if (!empty($matches[2] ?? "")) {
                $nmFunction = $matches[2];
            }
        }

        return [$nmClass, $nmExtension, $nmFunction];
    }
}

return index::getController();
