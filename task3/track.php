<?php
// Подключение к базе данных SQLite
$dsn = 'sqlite:' . __DIR__ . '/db/visits.db';
try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Чтение данных, отправленных клиентом
$data = json_decode(file_get_contents('php://input'), true);

$ip = $data['ip'];
$city = $data['city'];
$device = $data['device'];
$timestamp = $data['timestamp'];

// Запись данных в базу
$sql = "INSERT INTO visits (ip, city, device, timestamp) VALUES (:ip, :city, :device, :timestamp)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':ip' => $ip,
    ':city' => $city,
    ':device' => $device,
    ':timestamp' => $timestamp
]);

echo json_encode(['status' => 'success']);
