<?php
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Check if this is an API request
$isApiRequest = isset($url[0]) && $url[0] === 'api';

if ($isApiRequest) {
    // API routing
    $resource = isset($url[1]) ? $url[1] : '';
    $id = isset($url[2]) && is_numeric($url[2]) ? $url[2] : null;
    $action = isset($url[3]) ? $url[3] : '';

    // Map HTTP method to controller action
    $method = $_SERVER['REQUEST_METHOD'];

    // Handle method override for PUT/DELETE in forms
    if ($method === 'POST' && isset($_POST['_method'])) {
        $method = strtoupper($_POST['_method']);
    }

    if ($resource === 'products') {
        require_once 'app/controllers/ProductApiController.php';
        $controller = new ProductApiController();

        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->show($id);
                } else {
                    $controller->index();
                }
                break;
            case 'POST':
                if ($id) {
                    // Handle POST with file uploads for update
                    $controller->update($id);
                } else {
                    $controller->store();
                }
                break;
            case 'PUT':
                if ($id) {
                    $controller->update($id);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $controller->destroy($id);
                }
                break;
            default:
                header('Content-Type: application/json');
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit;
        }
    } elseif ($resource === 'categories') {
        require_once 'app/controllers/CategoryApiController.php';
        $controller = new CategoryApiController();

        switch ($method) {
            case 'GET':
                if ($id) {
                    $controller->show($id);
                } else {
                    $controller->index();
                }
                break;
            case 'POST':
                if ($id) {
                    // Handle POST with method override for update
                    $controller->update($id);
                } else {
                    $controller->store();
                }
                break;
            case 'PUT':
                if ($id) {
                    $controller->update($id);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $controller->destroy($id);
                }
                break;
            default:
                header('Content-Type: application/json');
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                exit;
        }
    }elseif ($resource === 'orders' && $method === 'POST') {
        require_once 'app/controllers/ProductApiController.php';
        $controller = new ProductApiController();
        $controller->createOrder();
    } else {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['error' => 'Resource not found']);
        exit;
    }
} else {
    // Original web routing
    $controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';
    $action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

    if (!file_exists('app/controllers/' . $controllerName . '.php')) {
        die('Controller not found');
    }
    require_once 'app/controllers/' . $controllerName . '.php';
    $controller = new $controllerName();

    if (!method_exists($controller, $action)) {
        die('Action not found');
    }

    call_user_func_array([$controller, $action], array_slice($url, 2));
}
