<?php

namespace App\Models;

use Config\Db;
use PDO;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::getInstance(); // Assumes Db::getInstance() initializes the PDO connection
    }

    public function checkLimit(string $username): int
    {
        return $this->db->query('SELECT limit_check FROM user_limits WHERE username = ?', [$username])->fetchColumn();
    }

    //1. **`get_user_by_email`**
    //1. **`get_user_by_oauth`**
    //1. **`create_user`**

    public function checkUserCredentials(array $credentials)
    {
        $result = $this->db->query(
            'SELECT * FROM users WHERE username = ? AND password = ?',
            [$credentials['username'], $credentials['password']]
        )->fetch();

        return $result ?: '0';
    }

    public function register(array $data): false|string
    {
        $query = 'INSERT INTO users (username, name, email, mobile, password, user_type)
              VALUES (:username, :name, :email, :mobile, :password, :type)';

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':mobile', $data['mobile']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':type', $data['type']);

        $options = [
            'memory_cost' => 1 << 16, // 64 MB (adjust based on server capacity)
            'time_cost'   => 4,      // Number of iterations
            'threads'     => 2       // Parallel threads
        ];
        $password_hash = password_hash($data['password'], PASSWORD_ARGON2ID, $options);
        $stmt->bindParam(':password', $password_hash);

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function findUserByMailOrNumber($email, $mobile)
    {
        $query = 'SELECT id FROM users WHERE email = :email OR mobile = :mobile LIMIT 1';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $result;
    }

    public function updateUser(array $data): void
    {
        $query = 'UPDATE users SET name = :name, email = :email, mobile = :mobile WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':mobile', $data['mobile']);
    }

}