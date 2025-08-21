<?php

namespace App\Models;

use Config\Db;
use PDO;

class AuthModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::getInstance(); // Assumes Db::getInstance() initializes the PDO connection
    }
    public function storeOtp(string $mobile, int $otp): void
    {
        $query = 'INSERT INTO otp_codes (mobile, otp) VALUES (:mobile, :otp)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function verifyOtp(string $mobile, int $otp): bool
    {
        $mobile = '+995'.$mobile;

        $query = 'SELECT id, otp FROM otp_codes WHERE mobile = :mobile AND created_at >= NOW() - INTERVAL 2 MINUTE AND is_used = 0 ORDER BY created_at DESC LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();

        $lastOtp = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if (!$lastOtp || $lastOtp['otp'] !== (string)$otp) return false;

        $updateQuery = 'UPDATE otp_codes SET is_used = 1 WHERE id = :id';
        $updateStmt = $this->db->prepare($updateQuery);
        $updateStmt->bindParam(':id', $lastOtp['id']);
        $updateStmt->execute();
        $updateStmt->closeCursor();

        return true;
    }

    public function activateUser(string $mobile): void
    {
        $query = 'UPDATE users SET status = \'active\' WHERE mobile = :mobile';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function findUserByMailOrNumber($identifier)
    {
        $query = 'SELECT * FROM users WHERE email = :email OR mobile = :mobile LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $identifier);
        $stmt->bindParam(':mobile', $identifier);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $result;
    }

    public function storeAccessToken($user_id, $token, $expires_at): void
    {

        $query = 'INSERT INTO access_tokens (user_id, token, created_at, expires_at) VALUES (:user_id, :token, NOW(), :expires_at)';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expires_at);
        $stmt->execute();
        $stmt->closeCursor();
    }

}