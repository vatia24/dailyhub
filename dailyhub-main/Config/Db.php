<?php

namespace Config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Db
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        // Load environment variables once (outside the Db class, ideally in your bootstrap)
        static $dotenv = null;
        if ($dotenv === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }

        if (!self::$instance) {
            try {
                $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
                $port = (int)($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 3306);
                $name = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
                $user = $_ENV['DB_USER'] ?? $_ENV['DB_USERNAME'] ?? getenv('DB_USER') ?: getenv('DB_USERNAME');
                $pass = $_ENV['DB_PASS'] ?? $_ENV['DB_PASSWORD'] ?? getenv('DB_PASS') ?: getenv('DB_PASSWORD');

                $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);
                $options = [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => (int)($_ENV['DB_CONNECT_TIMEOUT'] ?? getenv('DB_CONNECT_TIMEOUT') ?: 5),
                ];
                $sslCa = $_ENV['DB_SSL_CA'] ?? getenv('DB_SSL_CA') ?: '';
                if ($sslCa !== '') {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
                    if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
                        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = (bool)($_ENV['DB_SSL_VERIFY'] ?? getenv('DB_SSL_VERIFY') ?: false);
                    }
                }

                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}//class Db
//{
//    private static ?PDO $instance = null;
//
//    public static function getInstance(): PDO
//    {
//
//        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
//        $dotenv->load();
//
//        if (!self::$instance) {
//            try {
//                self::$instance = new PDO(
//                    sprintf(
//                        'mysql:host=%s;dbname=%s;charset=utf8mb4',
//                        $_ENV['DB_HOST'],
//                        $_ENV['DB_NAME']
//                    ),
//                    $_ENV['DB_USER'],
//                    $_ENV['DB_PASS']
//                );
//                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//            } catch (PDOException $e) {
//                throw new PDOException('Could not connect to the database. Please try again later. '.$e->getMessage());
//            }
//        }
//        return self::$instance;
//    }
//}
