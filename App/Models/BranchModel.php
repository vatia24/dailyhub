<?php

namespace App\Models;

use Config\Db;
use PDO;

class BranchModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public function upsertBranch(array $data): int
    {
        if (!empty($data['id'])) {
            $stmt = $this->db->prepare('UPDATE company_branches SET company_id=:company_id, branch_name=:branch_name, branch_address=:branch_address, branch_image=:branch_image WHERE id=:id');
            $stmt->bindParam(':id', $data['id']);
        } else {
            $stmt = $this->db->prepare('INSERT INTO company_branches (company_id, branch_name, branch_address, branch_image) VALUES (:company_id, :branch_name, :branch_address, :branch_image)');
        }
        $stmt->bindParam(':company_id', $data['company_id']);
        $stmt->bindParam(':branch_name', $data['branch_name']);
        $stmt->bindParam(':branch_address', $data['branch_address']);
        $stmt->bindParam(':branch_image', $data['branch_image']);
        $stmt->execute();
        $stmt->closeCursor();
        return (int)($data['id'] ?? $this->db->lastInsertId());
    }
}


