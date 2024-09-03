<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Подключение к базе данных SQLite
$dsn = 'sqlite:' . __DIR__ . '/db/visits.db';
try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}

// Получение данных для статистики
$sql = "SELECT * FROM visits";
$stmt = $pdo->query($sql);
$visits = $stmt->fetchAll(PDO::FETCH_ASSOC);

$hourlyVisits = [];
$cityVisits = [];

foreach ($visits as $visit) {
    $hour = (new DateTime($visit['timestamp']))->format('H');
    $hourlyVisits[$hour] = ($hourlyVisits[$hour] ?? 0) + 1;
    $cityVisits[$visit['city']] = ($cityVisits[$visit['city']] ?? 0) + 1;
}

// Подготовка данных для графиков
$hours = range(0, 23);
$visitsPerHour = array_map(function($hour) use ($hourlyVisits) {
    return $hourlyVisits[$hour] ?? 0;
}, $hours);

$cities = array_keys($cityVisits);
$cityCounts = array_values($cityVisits);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика посещений</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>Статистика посещений</h1>

<h2>График посещений по часам</h2>
<canvas id="hourlyVisitsChart"></canvas>

<h2>Посещения по городам</h2>
<canvas id="cityVisitsChart"></canvas>

<br>
<a href="logout.php">Выйти</a>

<script>
    // График посещений по часам
    const hourlyVisitsCtx = document.getElementById('hourlyVisitsChart').getContext('2d');
    const hourlyVisitsChart = new Chart(hourlyVisitsCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($hours) ?>,
            datasets: [{
                label: 'Количество уникальных посещений',
                data: <?= json_encode($visitsPerHour) ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Круговая диаграмма посещений по городам
    const cityVisitsCtx = document.getElementById('cityVisitsChart').getContext('2d');
    const cityVisitsChart = new Chart(cityVisitsCtx, {
        type:
        const cityVisitsChart = new Chart(cityVisitsCtx, {
            type: 'pie',
            data: {
                labels: <?= json_encode($cities) ?>,
                datasets: [{
                    label: 'Посещения по городам',
                    data: <?= json_encode($cityCounts) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
</script>
</body>
</html>
