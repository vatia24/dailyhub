<?php

namespace App\Models;

use Config\Db;
use PDO;

class DiscountModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    /**
     * List discounts with optional filters.
     * Supported filters: id, company_id, product_id, status (string|array), active_only (bool),
     * from_date (Y-m-d), to_date (Y-m-d), limit, offset
     *
     * @param array $filters
     * @return array
     */
    public function list(array $filters = []): array
    {
        $conditions = [];
        $namedParams = [];

        if (isset($filters['id'])) {
            $conditions[] = 'id = :id';
            $namedParams[':id'] = (int)$filters['id'];
        }
        if (isset($filters['company_id'])) {
            $conditions[] = 'company_id = :company_id';
            $namedParams[':company_id'] = (int)$filters['company_id'];
        }
        if (isset($filters['product_id'])) {
            $conditions[] = 'product_id = :product_id';
            $namedParams[':product_id'] = (int)$filters['product_id'];
        }
        $statusIn = null;
        if (isset($filters['status'])) {
            if (is_array($filters['status']) && !empty($filters['status'])) {
                $statusIn = $filters['status'];
                $conditions[] = 'status IN (' . implode(',', array_fill(0, count($statusIn), '?')) . ')';
            } else {
                $conditions[] = 'status = :status';
                $namedParams[':status'] = (string)$filters['status'];
            }
        }
        if (!empty($filters['active_only'])) {
            $conditions[] = '(start_date IS NULL OR start_date <= CURDATE())';
            $conditions[] = '(end_date IS NULL OR end_date >= CURDATE())';
            if (!isset($filters['status'])) {
                $conditions[] = "status = 'active'";
            }
        }
        if (!empty($filters['from_date'])) {
            $conditions[] = '(end_date IS NULL OR end_date >= :from_date)';
            $namedParams[':from_date'] = $filters['from_date'];
        }
        if (!empty($filters['to_date'])) {
            $conditions[] = '(start_date IS NULL OR start_date <= :to_date)';
            $namedParams[':to_date'] = $filters['to_date'];
        }

        $sql = 'SELECT * FROM discount';
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY id DESC';
        $hasLimit = isset($filters['limit']);
        $hasOffset = isset($filters['offset']);
        if ($hasLimit) $sql .= ' LIMIT :limit';
        if ($hasOffset) $sql .= ' OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($namedParams as $key => $val) {
            $stmt->bindValue($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        if ($statusIn) {
            $pos = 1;
            foreach ($statusIn as $st) {
                $stmt->bindValue($pos, (string)$st, PDO::PARAM_STR);
                $pos++;
            }
        }
        if ($hasLimit) $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
        if ($hasOffset) $stmt->bindValue(':offset', (int)$filters['offset'], PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    /**
     * Insert or update a discount.
     * Required keys: user_id, company_id, product_id, status.
     * One of discount_price or discount_percent may be provided (mutually exclusive).
     * Optional: start_date, end_date.
     *
     * @param array $data
     * @return int Newly inserted id or existing id on update
     */
    public function upsert(array $data): int
    {
        $isUpdate = !empty($data['id']);
        if ($isUpdate) {
            $stmt = $this->db->prepare('UPDATE discount SET user_id=:user_id, company_id=:company_id, product_id=:product_id, discount_price=:discount_price, discount_percent=:discount_percent, start_date=:start_date, end_date=:end_date, status=:status WHERE id=:id');
            $stmt->bindValue(':id', (int)$data['id'], PDO::PARAM_INT);
        } else {
            $stmt = $this->db->prepare('INSERT INTO discount (user_id, company_id, product_id, discount_price, discount_percent, start_date, end_date, status) VALUES (:user_id, :company_id, :product_id, :discount_price, :discount_percent, :start_date, :end_date, :status)');
        }

        $stmt->bindValue(':user_id', (int)$data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':company_id', (int)$data['company_id'], PDO::PARAM_INT);
        $stmt->bindValue(':product_id', (int)$data['product_id'], PDO::PARAM_INT);

        // Nullable numerics/dates
        if (array_key_exists('discount_price', $data) && $data['discount_price'] !== null && $data['discount_price'] !== '') {
            $stmt->bindValue(':discount_price', $data['discount_price']);
        } else {
            $stmt->bindValue(':discount_price', null, PDO::PARAM_NULL);
        }
        if (array_key_exists('discount_percent', $data) && $data['discount_percent'] !== null && $data['discount_percent'] !== '') {
            $stmt->bindValue(':discount_percent', $data['discount_percent']);
        } else {
            $stmt->bindValue(':discount_percent', null, PDO::PARAM_NULL);
        }
        if (!empty($data['start_date'])) {
            $stmt->bindValue(':start_date', $data['start_date']);
        } else {
            $stmt->bindValue(':start_date', null, PDO::PARAM_NULL);
        }
        if (!empty($data['end_date'])) {
            $stmt->bindValue(':end_date', $data['end_date']);
        } else {
            $stmt->bindValue(':end_date', null, PDO::PARAM_NULL);
        }
        $stmt->bindValue(':status', (string)$data['status']);

        $stmt->execute();
        $stmt->closeCursor();
        return $isUpdate ? (int)$data['id'] : (int)$this->db->lastInsertId();
    }

    /**
     * Bulk update status for multiple discounts.
     *
     * @param array $ids
     * @param string $status
     * @return int number of updated rows
     */
    public function bulkSetStatus(array $ids, string $status): int
    {
        if (empty($ids)) return 0;
        $in = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("UPDATE discount SET status = ? WHERE id IN ($in)");
        $params = array_merge([$status], $ids);
        $stmt->execute($params);
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    /**
     * Get discount by id.
     *
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM discount WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row ?: null;
    }

    /**
     * Delete discount by id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM discount WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $ok = $stmt->execute();
        $stmt->closeCursor();
        return (bool)$ok;
    }

    /**
     * Set status for a single discount.
     *
     * @param int $id
     * @param string $status
     * @return bool true if changed
     */
    public function setStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE discount SET status = :status WHERE id = :id');
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cnt = $stmt->rowCount();
        $stmt->closeCursor();
        return $cnt > 0;
    }

    /**
     * Get the latest active discount for a product (optionally scoped by company).
     * Active window: start_date <= today <= end_date, status = active.
     *
     * @param int $productId
     * @param int|null $companyId
     * @return array|null
     */
    public function getActiveByProduct(int $productId, ?int $companyId = null): ?array
    {
        $sql = 'SELECT * FROM discount WHERE product_id = :pid AND status = "active" AND (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE())';
        if ($companyId !== null) {
            $sql .= ' AND company_id = :cid';
        }
        $sql .= ' ORDER BY updated_at DESC LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        if ($companyId !== null) {
            $stmt->bindValue(':cid', $companyId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row ?: null;
    }
}


