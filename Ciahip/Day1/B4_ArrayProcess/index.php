<?php include "b4.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Array Processor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Array Processor</h2>
    <form method="POST">
        <input type="text" name="mang" placeholder="Nhập mảng (cách nhau bởi dấu phẩy)" 
               value="<?php echo isset($_POST['mang']) ? $_POST['mang'] : ''; ?>" required>
               <div class="actions">
            <button type="submit" name="submit">Xử lý</button>
            <!-- Xóa nội dung input ngay trên trình duyệt, không gửi form -->
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="btn-secondary">Làm mới</a>
        </div>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $arr = array_map('trim', explode(",", $_POST['mang']));
        $arr = array_map('floatval', $arr);

        echo '<div class="result">';
        echo "<p><strong>Mảng ban đầu:</strong> " . hienMang($arr) . "</p>";
        echo "<p><strong>Tổng các phần tử:</strong> " . tinhTong($arr) . "</p>";
        echo "<p><strong>Giá trị lớn nhất:</strong> " . timMax($arr) . "</p>";
        echo "<p><strong>Giá trị nhỏ nhất:</strong> " . timMin($arr) . "</p>";

        $sorted = sapXepTang($arr);
        echo "<p><strong>Mảng sau khi sắp xếp tăng:</strong> " . hienMang($sorted) . "</p>";

        echo "<p><strong>Số phần tử:</strong> " . demPhanTu($arr) . "</p>";
        echo '</div>';
    }
    ?>
</div>
</body>
</html>
