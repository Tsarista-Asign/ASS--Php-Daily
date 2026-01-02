<?php
// index.php - Trang chủ demo GET và POST
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
            background: #d9d4d0;
        }
        h1 {
            color: #333;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background: #908277;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn:hover {
            background: #6e5c4e;
        }
    </style>
</head>
<body>
    <?php
        echo "<h1>Demo PHP Form</h1>";
        echo '<a href="form_get.php" class="btn">Form GET</a>';
        echo '<a href="form_post.php" class="btn">Form POST</a>';
    ?>
</body>
</html>
