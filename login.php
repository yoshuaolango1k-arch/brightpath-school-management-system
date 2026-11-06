<?php
require_once __DIR__ . '/config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $_SESSION['login_error'] = 'Your session expired. Please try signing in again.';
    header('Location: index.php'); exit;
}

$email = trim(strtolower($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare('SELECT id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'The email or password you entered is incorrect.';
    header('Location: index.php'); exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];
unset($_SESSION['csrf_token']);

$destinations = [
    'admin' => 'admin/dashboard.php',
    'student' => 'students/dashboard.php',
    'instructor' => 'instructors/dashboard.php',
    'parent' => 'parents/dashboard.php'
];
header('Location: ' . ($destinations[$user['role']] ?? 'index.php'));
exit;
