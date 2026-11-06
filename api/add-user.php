<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_role('admin');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../admin/dashboard.php'); exit; }
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) { http_response_code(403); exit('Invalid request token. Refresh the dashboard and try again.'); }
$name = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';
$allowed = ['student','instructor','parent'];
if ($name === '' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8 || !in_array($role,$allowed,true)) {
    http_response_code(400); exit('Invalid details. Go back and check the form.');
}
try {
    $stmt=$pdo->prepare('INSERT INTO users (full_name,email,password,role) VALUES (?,?,?,?)');
    $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$role]);
    header('Location: ../admin/dashboard.php?created=1'); exit;
} catch (PDOException $e) {
    if ($e->getCode()==='23000') { http_response_code(409); exit('That email address is already in use. Go back and try another.'); }
    http_response_code(500); exit('Could not create this account. Please try again.');
}
