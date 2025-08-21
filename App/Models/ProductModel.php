<?php

namespace App\Models;

use Config\Db;
use PDO;
use Throwable;
use Exception;

class ProductModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::getInstance(); // Assumes Db::getInstance() initializes the PDO connection
    }

    /**
     * Fetch all products with their discounts.
     *
     * @return array
     * @throws Exception
     */
    public function getAllProductsWithDiscounts(): array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT p.*, d.*
                 FROM product p
                 LEFT JOIN discount d ON p.id = d.product_id'
            );
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new Exception('Error fetching all products: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Fetch a single product by ID with its discount.
     *
     * @param int $productId
     * @return array|null
     * @throws Exception
     */
    public function getProductWithDiscountById(int $productId): ?array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT p.*, d.*
                 FROM product p
                 LEFT JOIN discount d ON p.id = d.product_id
                 WHERE p.id = :productId'
            );
            $statement->bindParam(':productId', $productId, PDO::PARAM_INT);
            $statement->execute();

            $product = $statement->fetch(PDO::FETCH_ASSOC);
            return $product ?: null; // Return null if no product is found
        } catch (Throwable $e) {
            throw new Exception('Error fetching product by ID: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Add a new product.
     *
     * @param string $name
     * @param float $price
     * @return int ID of the newly created product
     * @throws Exception
     */
    public function addProduct(string $name, float $price): int
    {
        try {
            $statement = $this->db->prepare(
                'INSERT INTO product (name, price) VALUES (:name, :price)'
            );
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':price', $price, PDO::PARAM_STR);
            $statement->execute();

            return (int)$this->db->lastInsertId();
        } catch (Throwable $e) {
            throw new Exception('Error adding product: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a product.
     *
     * @param int $productId
     * @param string $name
     * @param float $price
     * @return bool
     * @throws Exception
     */
    public function updateProduct(int $productId, string $name, float $price): bool
    {
        try {
            $statement = $this->db->prepare(
                'UPDATE product 
                 SET name = :name, price = :price 
                 WHERE id = :productId'
            );
            $statement->bindParam(':productId', $productId, PDO::PARAM_INT);
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':price', $price, PDO::PARAM_STR);

            return $statement->execute();
        } catch (Throwable $e) {
            throw new Exception('Error updating product: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a product.
     *
     * @param int $productId
     * @return bool
     * @throws Exception
     */
    public function deleteProduct(int $productId): bool
    {
        try {
            $statement = $this->db->prepare(
                'DELETE FROM product WHERE id = :productId'
            );
            $statement->bindParam(':productId', $productId, PDO::PARAM_INT);

            return $statement->execute();
        } catch (Throwable $e) {
            throw new Exception('Error deleting product: ' . $e->getMessage(), $e->getCode());
        }
    }
}