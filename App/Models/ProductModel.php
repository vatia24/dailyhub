<?php

namespace App\Models;

use Config\Db;
use Exception;
use PDO;
use Throwable;

class ProductModel
{
    /**
     * @throws Exception
     */
    public function getProducts(array $properties): array
    {
        $db = Db::getInstance();

        try {
            // Execute the query
            $stmt = $db->query('SELECT * FROM product');

            // Check if the query execution was successful
            if (!$stmt) {
                throw new Exception('Database query failed: ' . implode(', ', $db->errorInfo()));
            }

            // Fetch all rows as an associative array
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the result set as an array
        } catch (Throwable $e) {
            // Log the error message or handle it accordingly
            throw new Exception('An error occurred while fetching products: ' . $e->getMessage());
        }
    }

    public function getSingleProduct(array $credentials)
    {
        // Example: Query database for user credentials
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM users WHERE username = ? AND password = ?',
            [$credentials['username'], $credentials['password']]
        )->fetch();

        return $result ?: '0';
    }

    public function resetLimit(string $username): void
    {
        $db = Db::getInstance();
        $db->query('UPDATE user_limits SET attempts = 0 WHERE username = ?', [$username]);
    }
}