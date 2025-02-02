<?php

namespace App\Models;

use Config\Db;

class UserModel
{
    public function checkLimit(string $username): int
    {
        // Example database interaction
        $db = Db::getInstance();
        return $db->query('SELECT limit_check FROM user_limits WHERE username = ?', [$username])->fetchColumn();
    }

    //1. **`get_user_by_email`**
    //1. **`get_user_by_oauth`**
    //1. **`create_user`**

    public function checkUserCredentials(array $credentials)
    {
        // Example: Query database for user credentials
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM users WHERE username = ? AND password = ?',
            [$credentials['username'], $credentials['password']]
        )->fetch();

        return $result ?: '0';
    }

    public function register(array $data): false|string
    {
        $db = Db::getInstance();
        $query = 'INSERT INTO users (username, name, email, mobile, password, user_type)
              VALUES (:username, :name, :email, :mobile, :password, :type)';

        $stmt = $db->prepare($query);
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

        return $db->lastInsertId();
    }


}