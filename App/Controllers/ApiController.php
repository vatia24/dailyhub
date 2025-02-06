<?php

namespace App\Controllers;

use App\Exceptions\ApiException;
use App\Helpers\JwtHelper;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\ProductService;
use App\Helpers\ResponseHelper;

class ApiController
{
    private AuthService $authService;
    private UserService $userService;
    private ProductService $productService;

    public function __construct(AuthService $authService, UserService $userService, ProductService $productService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->productService = $productService;
    }

    public function handleRequest(): void
    {
        try {
            $method = $this->extractMethodFromUrl();

            // Ensure the method is provided
            if (is_null($method)) {
                throw new ApiException(400, 'BAD_REQUEST', 'The "method" is required.');
            }

            // Define a mapping for method handlers
            $methodMap = $this->getMethodMap();

            // Check if the method exists in the mapping
            if (!array_key_exists($method, $methodMap)) {
                throw new ApiException(400, 'INVALID_METHOD', 'The requested method is not valid.');
            }

            // Route the request to appropriate handler
            $response = $this->routeRequest($method, $methodMap);

            // Send a successful response
            ResponseHelper::response(200, 'SUCCESS', $response);
        } catch (ApiException $e) {
            // Handle known API exceptions
            ResponseHelper::response($e->getStatusCode(), $e->getErrorCode(), $e->getMessage());
        } catch (\Throwable $e) {
            // Handle unexpected errors
            ResponseHelper::response(500,
                'INTERNAL_SERVER_ERROR',
                ['message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),]);
        }
    }

    /**
     * Defines valid API methods and their corresponding handlers.
     *
     * This mapping is used for both GET and POST requests.
     * @return array<string, callable>
     */
    private function getMethodMap(): array
    {
        return [
            'checkUserCredentials'  => [$this->userService, 'checkUserCredentials'],
            'registerUser'         => [$this->userService, 'registerUser'],
            'verifyCustomer'           => [$this->authService, 'verifyAndActivateUser'],
            'getProducts'           => [$this->productService, 'getProducts'],

        ];
    }

    /**
     * Routes request to the appropriate handler based on method and request type.
     *
     * @param string $method
     * @param array<string, callable> $methodMap
     * @return mixed
     * @throws ApiException
     */
    private function routeRequest(string $method, array $methodMap): mixed
    {
        $requestType = $_SERVER['REQUEST_METHOD'];
        //var_dump($requestType);

        if ($requestType === 'POST') {
            $body = $this->getJsonInput();
            return call_user_func($methodMap[$method], $body);
        }

        if ($requestType === 'GET') {
            $queryParams = $_GET;
            return call_user_func($methodMap[$method], $queryParams);
        }

        throw new ApiException(405, 'METHOD_NOT_ALLOWED', 'Only GET and POST methods are supported.');
    }

    /**
     * Extracts the method requested from the URL.
     *
     * @return string|null
     */
    private function extractMethodFromUrl(): ?string
    {
        $parts = parse_url($_SERVER['REQUEST_URI']);
        $path = trim($parts['path'], '/');
        $segments = explode('/', $path);

        return ($segments[0] === 'api' && isset($segments[1])) ? $segments[1] : null;
    }

    /**
     * Parses the JSON input and trims all fields recursively.
     *
     * @return array
     * @throws ApiException
     */
    private function getJsonInput(): array
    {
        // Retrieve raw input data
        $input = json_decode(file_get_contents('php://input'), true);

        // Validate JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(400, 'BAD_REQUEST', 'Invalid JSON input. Please check the syntax.');
        }

        return $this->trimRecursive($input);
    }

    /**
     * Recursively trim all values in an array or string.
     *
     * @param mixed $input
     * @return mixed
     */
    private function trimRecursive(mixed $input): mixed
    {
        if (is_array($input)) {
            return array_map([$this, 'trimRecursive'], $input);
        }

        if (is_string($input)) {
            return trim($input);
        }

        return $input;
    }


    // i want better place for that functions
    /**
     * @throws ApiException
     */
    public function authorizeRequest(): \stdClass
    {
        $headers = getallheaders(); // Get request headers
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader) {
            throw new ApiException(401, 'UNAUTHORIZED', 'Authorization header not found');
        }

        $token = str_replace('Bearer ', '', $authHeader); // Extract the token
        $decodedToken = JwtHelper::validateToken($token);

        if (!$decodedToken) {
            throw new ApiException(401, 'INVALID_TOKEN', 'Invalid or expired token');
        }

        return $decodedToken; // Return the token if valid
    }

    /**
     * @throws ApiException
     */
    public function facebookAuth(): void
    {
        $accessToken = $_GET['code']; // Get 'code' from Facebook OAuth redirect
        try {
            $result = $this->authService->handleFacebookAuth($accessToken);
            ResponseHelper::response(200, 'SUCCESS', $result);
        } catch (\Exception $e) {
            throw new ApiException(401, 'CANNOT_AUTHORIZE', $e->getMessage());
        }
    }

    /**
     * @throws ApiException
     */
    public function googleAuth(): void
    {
        $accessToken = $_GET['code']; // Get 'code' from Google OAuth redirect
        try {
            $result = $this->authService->handleGoogleAuth($accessToken);
            ResponseHelper::response(200, 'SUCCESS', $result);
        } catch (\Exception $e) {
            throw new ApiException(401, 'CANNOT_AUTHORIZE', $e->getMessage());
        }
    }
}