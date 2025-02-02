<?php

namespace App\Helpers;

use JetBrains\PhpStorm\NoReturn;

class ResponseHelper
{
    #[NoReturn] public static function response(int $status, string $type, $data = []): void
    {
        header('Content-Type: application/json; charset=utf-8', true, $status);
        echo json_encode(['statusCode' => $status, 'type' => $type, 'data' => $data]);
        exit;
    }

}

