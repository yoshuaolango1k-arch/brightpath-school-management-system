<?php
// Update these values if your XAMPP MySQL settings are different.
$host = 'localhost';
$dbname = 'school_management';
$username = 'root';
$password = ''; // XAMPP's default MySQL password is usually blank.

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed. Check config/database.php and make sure MySQL is running.');
}
