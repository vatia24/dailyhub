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
     * Fetch all products with their latest active discount (if any).
     * Adds aliased discount fields and computed effective_price.
     *
     * @return array
     * @throws Exception
     */
    public function getAllProductsWithDiscounts(): array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT 
                    p.*,
                    d.id AS discount_id,
                    d.discount_price,
                    d.discount_percent,
                    d.start_date AS discount_start_date,
                    d.end_date AS discount_end_date,
                    d.status AS discount_status,
                    d.created_at AS discount_created_at,
                    d.updated_at AS discount_updated_at,
                    CASE 
                        WHEN d.discount_price IS NOT NULL THEN d.discount_price
                        WHEN d.discount_percent IS NOT NULL THEN ROUND(p.price * (1 - d.discount_percent/100), 2)
                        ELSE p.price
                    END AS effective_price
                 FROM product p
                 LEFT JOIN discount d 
                   ON d.product_id = p.id
                  AND d.status = "active"
                  AND (d.start_date IS NULL OR d.start_date <= CURDATE())
                  AND (d.end_date IS NULL OR d.end_date >= CURDATE())
                  AND d.updated_at = (
                        SELECT MAX(d2.updated_at) 
                        FROM discount d2 
                        WHERE d2.product_id = p.id 
                          AND d2.status = "active"
                          AND (d2.start_date IS NULL OR d2.start_date <= CURDATE())
                          AND (d2.end_date IS NULL OR d2.end_date >= CURDATE())
                  )'
            );
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new Exception('Error fetching all products: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Fetch a single product by ID with its latest active discount.
     * Adds aliased discount fields and computed effective_price.
     *
     * @param int $productId
     * @return array|null
     * @throws Exception
     */
    public function getProductWithDiscountById(int $productId): ?array
    {
        try {
            $statement = $this->db->prepare(
                'SELECT 
                    p.*,
                    d.id AS discount_id,
                    d.discount_price,
                    d.discount_percent,
                    d.start_date AS discount_start_date,
                    d.end_date AS discount_end_date,
                    d.status AS discount_status,
                    d.created_at AS discount_created_at,
                    d.updated_at AS discount_updated_at,
                    CASE 
                        WHEN d.discount_price IS NOT NULL THEN d.discount_price
                        WHEN d.discount_percent IS NOT NULL THEN ROUND(p.price * (1 - d.discount_percent/100), 2)
                        ELSE p.price
                    END AS effective_price
                 FROM product p
                 LEFT JOIN discount d 
                   ON d.product_id = p.id
                  AND d.status = "active"
                  AND (d.start_date IS NULL OR d.start_date <= CURDATE())
                  AND (d.end_date IS NULL OR d.end_date >= CURDATE())
                  AND d.updated_at = (
                        SELECT MAX(d2.updated_at) 
                        FROM discount d2 
                        WHERE d2.product_id = p.id 
                          AND d2.status = "active"
                          AND (d2.start_date IS NULL OR d2.start_date <= CURDATE())
                          AND (d2.end_date IS NULL OR d2.end_date >= CURDATE())
                  )
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