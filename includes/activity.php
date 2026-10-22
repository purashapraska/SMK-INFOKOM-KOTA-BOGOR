<?php

declare(strict_types=1);

function log_activity(string $action, string $description = ''): void
{
    global $pdo;

    try {
        $userId = $_SESSION['user_id'] ?? null;

        $statement = $pdo->prepare(
            'INSERT INTO activity_logs ' .
            '(user_id, action, description, ip_address, user_agent) ' .
            'VALUES (?, ?, ?, ?, ?)' 
        );

        $statement->execute([
            $userId ? (int) $userId : null,
            $action,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
        ]);
    } catch (Throwable) {
        // Activity logging must never break the main request.
    }
}
