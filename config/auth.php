<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_role(string $role): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: ../index.php');
        exit;
    }

    if (($_SESSION['role'] ?? '') !== $role) {
        $destinations = [
            'admin' => '../admin/dashboard.php',
            'student' => '../students/dashboard.php',
            'instructor' => '../instructors/dashboard.php',
            'parent' => '../parents/dashboard.php'
        ];
        header('Location: ' . ($destinations[$_SESSION['role']] ?? '../index.php'));
        exit;
    }
}
