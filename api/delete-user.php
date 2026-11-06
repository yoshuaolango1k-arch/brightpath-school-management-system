<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
require_role('admin');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../admin/dashboard.php'); exit; }
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) { http_response_code(403); exit('Invalid request token. Refresh the dashboard and try again.'); }
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id === (int)$_SESSION['user_id']) { header('Location: ../admin/dashboard.php'); exit; }
$stmt=$pdo->prepare('DELETE FROM users WHERE id = ? AND role <> "admin"');
$stmt->execute([$id]);
header('Location: ../admin/dashboard.php'); exit;
