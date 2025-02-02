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

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        if (!self::$instance) {
            try {
                self::$instance = new PDO(
                    sprintf(
                        'mysql:host=%s;dbname=%s;charset=utf8mb4',
                        $_ENV['DB_HOST'],
                        $_ENV['DB_NAME']
                    ),
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS']
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException('Could not connect to the database. Please try again later. '.$e->getMessage());
            }
        }
        return self::$instance;
    }
}