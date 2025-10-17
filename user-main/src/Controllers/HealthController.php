<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Utils\Response;
use App\Utils\Request;
use App\Config\Database;

final class HealthController
{
    public function __construct(private Request $req, private Response $res)
    {
    }

    public function health(): void
    {
        $this->res->json(['status' => 'ok']);
    }

    public function db(): void
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->query('SELECT 1');
            $stmt->fetch();
            $this->res->json(['status' => 'ok', 'db' => 'connected']);
        } catch (\Throwable $e) {
            $this->res->json([
                'status' => 'error',
                'db' => 'unavailable',
                'message' => (($_ENV['APP_ENV'] ?? 'production') === 'development') ? $e->getMessage() : 'connection failed'
            ], 500);
        }
    }
}


