<?php
require_once __DIR__ . '/config/database.php';
session_start();

function show_message(string $message, bool $success = false): void {
    $title = $success ? 'Account created' : 'Registration not completed';
    $color = $success ? 'text-teal-700' : 'text-red-700';
    echo '<!doctype html><html lang="en"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script><body class="grid min-h-screen place-items-center bg-slate-50 p-5"><main class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl"><h1 class="text-2xl font-bold '.$color.'">'.htmlspecialchars($title).'</h1><p class="mt-3 text-slate-600">'.htmlspecialchars($message).'</p><a class="mt-6 inline-block rounded-xl bg-slate-900 px-5 py-3 font-semibold text-white" href="index.php">Go to sign in</a></main></body></html>';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: register.html'); exit; }

$name = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $password !== $confirm) {
    show_message('Please check your name, email and password. Passwords must match and contain at least 8 characters.');
}
try {
    $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$email]);
    if ($check->fetch()) show_message('That email address is already registered. Try signing in instead.');
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'student')");
    $insert->execute([$name, $email, $hash]);
    show_message('Your student account is ready. You can now sign in using the common login page.', true);
} catch (PDOException $e) {
    show_message('Something went wrong while creating the account. Please try again.');
}
