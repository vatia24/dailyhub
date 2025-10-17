<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $conn = null;

    public static function connection(): PDO
    {
        if (self::$conn) {
            return self::$conn;
        }
        $get = function (array $keys, $default = null) {
            foreach ($keys as $k) {
                if (isset($_ENV[$k]) && $_ENV[$k] !== '') return $_ENV[$k];
                $v = getenv($k);
                if ($v !== false && $v !== '') return $v;
                if (isset($_SERVER[$k]) && $_SERVER[$k] !== '') return $_SERVER[$k];
            }
            return $default;
        };
        
        // Support multiple common env names (DO/Heroku styles)
        $host = (string)$get(['DB_HOST','DBHOST','MYSQL_HOST','MYSQLHOST'], 'sumoapp-do-user-18974331-0.g.db.ondigitalocean.com');
        $port = (int)$get(['DB_PORT','DBPORT','MYSQL_PORT','MYSQLPORT'], 25060);
        $db   = (string)$get(['DB_NAME','DB_DATABASE','MYSQL_DATABASE','MYSQLDB','MYSQLDATABASE'], 'Sumo');
        $user = (string)$get(['DB_USER','DB_USERNAME','MYSQL_USER','MYSQLUSER'], 'doadmin');
        $pass = (string)$get(['DB_PASS','DB_PASSWORD','MYSQL_PASSWORD','MYSQLPWD','MYSQLPASS'], 'AVNS_bwUIKwjWNFdm2LDNUMJ');
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => (int)$get(['DB_CONNECT_TIMEOUT'], 5),
        ];
        // Optional SSL configuration for Managed DBs
        $sslCa = (string)$get(['DB_SSL_CA','MYSQL_SSL_CA'], '');
        if ($sslCa !== '') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
            // Some providers also need verify server cert toggle
            if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
                $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = (bool)$get(['DB_SSL_VERIFY','MYSQL_SSL_VERIFY'], false);
            }
        }
        try {
            self::$conn = new PDO($dsn, $user, $pass, $options);
            return self::$conn;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'DB connection failed',
                'details' => (($_ENV['APP_ENV'] ?? 'production') === 'development') ? $e->getMessage() : null,
                'host' => $host,
                'port' => $port,
                'db' => $db,
            ]);
            exit;
        }
    }
}

?>


