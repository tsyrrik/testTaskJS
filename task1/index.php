<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Загрузка .txt файла</h1>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="txtfile" accept=".txt">
    <button type="submit" name="upload">Загрузить</button>
</form>

<?php
if (isset($_POST['upload'])) {
    $upload_dir = 'files/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file = $_FILES['txtfile'];
    $upload_file = $upload_dir . basename($file['name']);

    if ($file['type'] == 'text/plain' && move_uploaded_file($file['tmp_name'], $upload_file)) {
        echo '<div class="status success"></div>';
        echo '<h2>Содержимое файла:</h2>';

        $content = file_get_contents($upload_file);
        $delimiter = ',';
        $lines = explode($delimiter, $content);

        foreach ($lines as $line) {
            $digit_count = preg_match_all('/\d/', $line);
            echo "<p>$line = $digit_count</p>";
        }
    } else {
        echo '<div class="status error"></div>';
        echo '<p>Ошибка загрузки файла.</p>';
    }
}
?>
</body>
</html>
