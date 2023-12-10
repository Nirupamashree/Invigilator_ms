<?php
// Database connection (similar to your existing code)
$host = 'sqlserver43.mysql.database.azure.com';
$db = 'user1_db';
$user = 'nirupamashree';
$password = 'password@123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

$facultyName = $_GET['faculty_name'] ?? '';
$response = [];

if (!empty($facultyName)) {
    $stmt = $pdo->prepare("SELECT * FROM $facultyName");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['data'] = $result;
} else {
    $response['error'] = "Invalid faculty name.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
