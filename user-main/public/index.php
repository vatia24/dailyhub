<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$__envBase = dirname(__DIR__);
if (is_file($__envBase . DIRECTORY_SEPARATOR . '.env')) {
    $dotenv = Dotenv::createImmutable($__envBase);
    $dotenv->safeLoad();
} elseif (is_file($__envBase . DIRECTORY_SEPARATOR . '.env.example')) {
    $dotenv = Dotenv::createImmutable($__envBase, '.env.example');
    $dotenv->safeLoad();
}

// Ensure unhandled errors are returned as JSON (helps clients avoid "can't parse JSON")
set_exception_handler(function ($e) {
    $isDev = (($_ENV['APP_ENV'] ?? 'production') === 'development');
    http_response_code(500);
    header('Content-Type: application/json');
    $payload = ['error' => 'Internal Server Error'];
    if ($isDev) {
        $payload['exception'] = is_object($e) ? get_class($e) : 'Error';
        $payload['message'] = is_object($e) && method_exists($e, 'getMessage') ? $e->getMessage() : (string)$e;
    }
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
});

set_error_handler(function ($severity, $message, $file, $line) {
    // Convert errors to exceptions so they get handled above
    throw new ErrorException($message, 0, $severity, $file, $line);
});

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Fatal Error'], JSON_UNESCAPED_SLASHES);
    }
});

use App\Utils\Router;
use App\Utils\Request;
use App\Utils\Response;
use App\Utils\RateLimiter;

// Support running behind a path prefix (e.g., /api-user). When BASE_PATH is set,
// we strip it from REQUEST_URI so the router can continue matching absolute paths
// like /api/discounts.
// Be robust in how we read BASE_PATH in different environments.
$__basePathRaw = getenv('BASE_PATH');
if ($__basePathRaw === false || $__basePathRaw === '') {
    $__basePathRaw = $_ENV['BASE_PATH'] ?? ($_SERVER['BASE_PATH'] ?? '');
}
$basePath = rtrim((string)$__basePathRaw, '/');

// If BASE_PATH isn't provided, try to infer it from the current request URI.
// This helps when deployed behind an ingress path prefix without env configured.
if ($basePath === '' && isset($_SERVER['REQUEST_URI'])) {
    $uriPath = (string)parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // If the request includes /api/ segment, everything before it is the base prefix.
    $apiPos = strpos($uriPath, '/api/');
    if ($apiPos !== false && $apiPos > 0) {
        $basePath = rtrim(substr($uriPath, 0, $apiPos), '/');
    } elseif ($uriPath !== '') {
        // If hitting swagger endpoints, infer from their directory.
        if (str_ends_with($uriPath, '/swagger') || str_ends_with($uriPath, '/swagger/')) {
            $basePath = rtrim(dirname($uriPath), '/');
        } elseif (str_ends_with($uriPath, '/swagger.yaml')) {
            $basePath = rtrim(dirname($uriPath), '/');
        }
    }
}

if ($basePath !== '' && isset($_SERVER['REQUEST_URI'])) {
    $uri = $_SERVER['REQUEST_URI'];
    if (str_starts_with($uri, $basePath . '/')) {
        $_SERVER['REQUEST_URI'] = substr($uri, strlen($basePath));
    } elseif ($uri === $basePath) {
        // Redirect bare prefix to slash-suffixed path so relative assets resolve
        $loc = $basePath . '/';
        header('Location: ' . $loc, true, 301);
        exit;
    }
}

header('Content-Type: application/json');
// Basic CORS and security headers (mirrors dailyhub-main defaults)
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = array_filter(array_map('trim', explode(',', (string)($_ENV['ALLOWED_ORIGINS'] ?? ''))));
$isDev = (($_ENV['APP_ENV'] ?? 'production') === 'development');

if ($isDev || ($origin && in_array($origin, $allowedOrigins, true))) {
    if ($origin) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Vary: Origin');
        header('Access-Control-Allow-Credentials: true');
    } else {
        header('Access-Control-Allow-Origin: *');
    }
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 600');
}

header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header('X-Frame-Options: DENY');
if (!$isDev && ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'))) {
    header('Strict-Transport-Security: max-age=15552000; includeSubDomains');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$router = new Router(new Request(), new Response(), new RateLimiter(dirname(__DIR__) . '/storage/ratelimit'));

(require __DIR__ . '/../src/routes.php')($router);

$router->dispatch();

