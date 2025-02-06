<?php

use Dotenv\Dotenv;
use App\Controllers\ApiController;
use App\Exceptions\ApiException;

// Load environment variables using Dotenv
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Enable or disable error reporting dynamically based on the environment
if ($_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1); // Show errors in development
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); // Report all types of errors
} else {
    ini_set('display_errors', 0); // Hide errors in production
    ini_set('display_startup_errors', 0);
    error_reporting(0); // Suppress error reporting
}

// Define routes using FastRoute
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // Route for POST requests to /api
    $r->addRoute('POST', '/api/{method}', [ApiController::class, 'handleRequest']);
    $r->addRoute('GET', '/api/{method}', [ApiController::class, 'handleRequest']);
});

// Fetch the current HTTP request method and URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Remove the query string and decode the URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

try {
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            http_response_code(404);
            echo json_encode(['error' => '404 - Not Found']);
            break;

        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            http_response_code(405);
            echo json_encode(['error' => '405 - Method Not Allowed']);
            break;

        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];

            $method = ucfirst($vars['method']); // Extract method from URI
            [$class, $action] = $handler;

            $autModel = new \App\Models\AuthModel();
            $userModel = new \App\Models\UserModel();
            $productModel = new \App\Models\ProductModel();

            // Manually create required dependencies
            $authService = new \App\Services\AuthService($autModel, require_once __DIR__ . '/Config/AuthConfig.php');
            $userService = new \App\Services\UserService($userModel, $authService);
            $productService = new \App\Services\ProductService($productModel);

            // Create the instance of the controller with the required dependencies
            $controller = new $class($authService, $userService, $productService);

            // Call the action
            $controller->$action();
            break;

    }
} catch (ApiException $e) {
    // Handle custom `ApiException`
    http_response_code($e->getStatusCode());
    echo json_encode([
        'error_code' => $e->getErrorCode(),
        'message' => $e->getMessage(),
    ]);
} catch (Throwable $e) {
    // Handle all other unexpected errors
    http_response_code(500);
    if ($_ENV['APP_ENV'] === 'development') {
        // Show detailed error trace in development mode
        echo json_encode([
            'error' => 'Unexpected server error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    } else {
        // Return a generic message in production
        echo json_encode([
            'error' => 'Internal server error',
            'message' => 'An error has occurred. Please try again later.',
        ]);
    }
}