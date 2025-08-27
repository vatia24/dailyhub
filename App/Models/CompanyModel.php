<?php

namespace App\Models;

use Config\Db;
use PDO;

class CompanyModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }

    public function getCompanyById(int $companyId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM companies WHERE id = :id');
        $stmt->bindParam(':id', $companyId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row ?: null;
    }

    public function upsertCompany(array $data): int
    {
        if (!empty($data['id'])) {
            $stmt = $this->db->prepare('UPDATE companies SET full_name=:full_name, address=:address, city=:city, postal_code=:postal_code, country=:country WHERE id=:id');
            $stmt->bindParam(':id', $data['id']);
        } else {
            $stmt = $this->db->prepare('INSERT INTO companies (user_id, full_name, address, city, postal_code, country) VALUES (:user_id, :full_name, :address, :city, :postal_code, :country)');
            $stmt->bindParam(':user_id', $data['user_id']);
        }
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':postal_code', $data['postal_code']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->execute();
        $stmt->closeCursor();
        return (int)($data['id'] ?? $this->db->lastInsertId());
    }

    public function getUserRoleForCompany(int $userId, int $companyId): ?string
    {
        // Owner if matches companies.user_id
        $stmt = $this->db->prepare('SELECT CASE WHEN c.user_id = :uid THEN "Owner" ELSE NULL END as role FROM companies c WHERE c.id = :cid');
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $owner = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if (!empty($owner['role'])) return 'Owner';

        // Otherwise try sub_user role
        $stmt = $this->db->prepare('SELECT role FROM sub_user WHERE user_id = :uid AND company_id = :cid AND active = 1 LIMIT 1');
        $stmt->bindParam(':uid', $userId);
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row['role'] ?? null;
    }

    public function setStatus(int $companyId, string $status): void
    {
        $stmt = $this->db->prepare('UPDATE companies SET status = :status WHERE id = :id');
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $companyId);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Hours
    public function replaceHours(int $companyId, array $hours): void
    {
        $this->db->beginTransaction();
        $del = $this->db->prepare('DELETE FROM company_hours WHERE company_id = :cid');
        $del->bindParam(':cid', $companyId);
        $del->execute();

        $ins = $this->db->prepare('INSERT INTO company_hours (company_id, day_of_week, open_time, close_time, is_closed) VALUES (:cid, :dow, :open_time, :close_time, :is_closed)');
        foreach ($hours as $row) {
            $ins->bindValue(':cid', $companyId);
            $ins->bindValue(':dow', (int)$row['day_of_week']);
            $ins->bindValue(':open_time', $row['open_time']);
            $ins->bindValue(':close_time', $row['close_time']);
            $ins->bindValue(':is_closed', (int)($row['is_closed'] ?? 0));
            $ins->execute();
        }
        $this->db->commit();
    }

    public function getHours(int $companyId): array
    {
        $stmt = $this->db->prepare('SELECT day_of_week, open_time, close_time, is_closed FROM company_hours WHERE company_id = :cid ORDER BY day_of_week');
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    // Socials
    public function addSocial(int $companyId, string $platform, string $url): int
    {
        $stmt = $this->db->prepare('INSERT INTO company_socials (company_id, platform, url) VALUES (:cid, :platform, :url)');
        $stmt->bindParam(':cid', $companyId);
        $stmt->bindParam(':platform', $platform);
        $stmt->bindParam(':url', $url);
        $stmt->execute();
        $stmt->closeCursor();
        return (int)$this->db->lastInsertId();
    }

    public function listSocials(int $companyId): array
    {
        $stmt = $this->db->prepare('SELECT id, platform, url FROM company_socials WHERE company_id = :cid ORDER BY id');
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    public function deleteSocial(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM company_socials WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Gallery
    public function addGallery(int $companyId, string $path): int
    {
        $stmt = $this->db->prepare('INSERT INTO company_gallery (company_id, path) VALUES (:cid, :path)');
        $stmt->bindParam(':cid', $companyId);
        $stmt->bindParam(':path', $path);
        $stmt->execute();
        $stmt->closeCursor();
        return (int)$this->db->lastInsertId();
    }

    public function listGallery(int $companyId): array
    {
        $stmt = $this->db->prepare('SELECT id, path FROM company_gallery WHERE company_id = :cid ORDER BY id');
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    public function deleteGallery(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM company_gallery WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Documents
    public function addDocument(int $companyId, string $docType, string $path): int
    {
        $stmt = $this->db->prepare('INSERT INTO company_documents (company_id, doc_type, path) VALUES (:cid, :type, :path)');
        $stmt->bindParam(':cid', $companyId);
        $stmt->bindParam(':type', $docType);
        $stmt->bindParam(':path', $path);
        $stmt->execute();
        $stmt->closeCursor();
        return (int)$this->db->lastInsertId();
    }

    public function listDocuments(int $companyId): array
    {
        $stmt = $this->db->prepare('SELECT id, doc_type, path, status, uploaded_at, reviewed_at, reviewer_id FROM company_documents WHERE company_id = :cid ORDER BY id');
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    public function reviewDocument(int $docId, string $status, ?int $reviewerId = null): void
    {
        $stmt = $this->db->prepare('UPDATE company_documents SET status = :status, reviewed_at = NOW(), reviewer_id = :rid WHERE id = :id');
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':rid', $reviewerId);
        $stmt->bindParam(':id', $docId);
        $stmt->execute();
        $stmt->closeCursor();
    }

    // Delivery zones
    public function upsertZone(array $data): int
    {
        $isUpdate = !empty($data['id']);
        if ($isUpdate) {
            $stmt = $this->db->prepare('UPDATE delivery_zones SET name=:name, zone_type=:zone_type, center_lat=:center_lat, center_lng=:center_lng, radius_m=:radius_m, polygon=:polygon WHERE id=:id AND company_id=:cid');
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':cid', $data['company_id']);
        } else {
            $stmt = $this->db->prepare('INSERT INTO delivery_zones (company_id, name, zone_type, center_lat, center_lng, radius_m, polygon) VALUES (:company_id, :name, :zone_type, :center_lat, :center_lng, :radius_m, :polygon)');
        }
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':zone_type', $data['zone_type']);
        $stmt->bindParam(':center_lat', $data['center_lat']);
        $stmt->bindParam(':center_lng', $data['center_lng']);
        $stmt->bindParam(':radius_m', $data['radius_m']);
        $stmt->bindParam(':polygon', $data['polygon']);
        if (!$isUpdate) { $stmt->bindParam(':company_id', $data['company_id']); }
        $stmt->execute();
        $stmt->closeCursor();
        return $isUpdate ? (int)$data['id'] : (int)$this->db->lastInsertId();
    }

    public function listZones(int $companyId): array
    {
        $stmt = $this->db->prepare('SELECT id, name, zone_type, center_lat, center_lng, radius_m, polygon FROM delivery_zones WHERE company_id = :cid ORDER BY id');
        $stmt->bindParam(':cid', $companyId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    public function deleteZone(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM delivery_zones WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->closeCursor();
    }
}


