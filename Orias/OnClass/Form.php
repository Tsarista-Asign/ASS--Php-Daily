<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Slide 8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Nhập Thông Tin Sách</h2>

    <form action="">
        <label>Tên sách: <input type="text" name="book"></label><br><br>
        <label>Tác giả: <input type="text" name="owner"></label><br><br>
        <label>Nhà xuất bản: <input type="text" name="deployer"></label><br><br>
        <label>Năm xuất bản: <input type="number" name="year"></label><br><br>

        <!-- 2 nút gửi, mỗi nút có method riêng -->
        <button type="submit" formmethod="get">GET</button>
        <button type="submit" formmethod="post">POST</button>
    </form>

    <hr>

    <?php
    if (!empty($_GET)) {
        echo "<h3>Kết quả từ GET:</h3>";
        echo "Tên sách: " . htmlspecialchars($_GET['book'] ?? '') . "<br>";
        echo "Tác giả: " . htmlspecialchars($_GET['owner'] ?? '') . "<br>";
        echo "Nhà xuất bản: " . htmlspecialchars($_GET['deployer'] ?? '') . "<br>";
        echo "Năm xuất bản: " . htmlspecialchars($_GET['yẻ'] ?? '') . "<br>";
    }

    if (!empty($_POST)) {
        echo "<h3>Kết quả từ POST:</h3>";
        echo "Tên sách: " . htmlspecialchars($_POST['book'] ?? '') . "<br>";
        echo "Tác giả: " . htmlspecialchars($_POST['owner'] ?? '') . "<br>";
        echo "Nhà xuất bản: " . htmlspecialchars($_POST['deployer'] ?? '') . "<br>";
        echo "Năm xuất bản: " . htmlspecialchars($_POST['yẻ'] ?? '') . "<br>";
    }
    ?>
</body>
</html>
