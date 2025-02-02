<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static array $config;

    private static function getConfig(): array
    {
        if (!isset(self::$config)) {
            self::$config = require __DIR__ . '/../../Config/AuthConfig.php';
        }
        return self::$config;
    }
    public static function generateToken($data): string
    {
        $payload = [
            'iss' => 'discount',       // Issuer
            'iat' => time(),               // Issued at
            'exp' => time() + self::getConfig()['jwt_expiration'], // Expiration
            'data' => $data,               // Custom data (e.g., user info)
        ];

        return JWT::encode($payload, self::getConfig()['jwt_secret_key'], 'HS256');
    }

    public static function validateToken($token): ?\stdClass
    {

        try {
            return JWT::decode($token, new Key(self::getConfig()['jwt_secret_key'], 'HS256'));
        } catch (\Exception $e) {
            return null; // Or handle exception as necessary
        }
    }
}