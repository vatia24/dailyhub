<?php

namespace App\Models;

use Config\Db;
use PDO;

class AuthModel
{
    public function storeOtp(string $mobile, int $otp): void
    {
        $db = Db::getInstance();
        $query = 'INSERT INTO otp_codes (mobile, otp) VALUES (:mobile, :otp)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function verifyOtp(string $mobile, int $otp): bool
    {
        $mobile = '+995'.$mobile;

        $db = Db::getInstance();
        $query = 'SELECT id, otp FROM otp_codes WHERE mobile = :mobile AND created_at >= NOW() - INTERVAL 2 MINUTE AND is_used = 0 ORDER BY created_at DESC LIMIT 1';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();

        $lastOtp = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if (!$lastOtp || $lastOtp['otp'] !== (string)$otp) return false;

        $updateQuery = 'UPDATE otp_codes SET is_used = 1 WHERE id = :id';
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindParam(':id', $lastOtp['id']);
        $updateStmt->execute();
        $updateStmt->closeCursor();

        return true;
    }

    public function activateUser(string $mobile): void
    {
        $db = Db::getInstance();
        $query = 'UPDATE users SET status = \'active\' WHERE mobile = :mobile';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function findUserByMailOrNumber($identifier)
    {
        $db = Db::getInstance();
        $query = 'SELECT id, password, name, status FROM users WHERE email = :identifier OR mobile = :identifier LIMIT 1';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':identifier', $identifier);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $result;
    }

}