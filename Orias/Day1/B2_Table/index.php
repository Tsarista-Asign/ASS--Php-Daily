<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Table</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    // Tổng số dòng
    $total_lines = 100;

    // Số dòng mỗi trang
    $lines_per_page = 10;

    // Lấy trang hiện tại, mặc định là 1
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    if ($page < 1)
        $page = 1;

    // Tính tổng số trang
    $total_pages = ceil($total_lines / $lines_per_page);

    // Tính vị trí bắt đầu
    $start_line = ($page - 1) * $lines_per_page + 1;

    // Tính vị trí kết thúc
    $end_line = min($start_line + $lines_per_page - 1, $total_lines);

    // Hiển thị các dòng từ $start_line đến $end_line
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>STT</th><th>Tên sách</th><th>Nội dung</th></tr>";

    for ($i = $start_line; $i <= $end_line; $i++) {
        echo "<tr>";
        echo "<td>$i</td>";
        echo "<td>Tensach$i</td>";
        echo "<td>Noidung$i</td>";
        echo "</tr>";
    }

    echo "</table>";


    // Hiển thị nút điều hướng
    echo "<div style='margin-top:20px;'>";

    // Nút trang trước
    if ($page > 1) {
        $prev_page = $page - 1;
        echo "<a href='?page=$prev_page'>Trang trước</a> ";
    }

    // Các nút số trang
    for ($p = 1; $p <= $total_pages; $p++) {
        if ($p == $page) {
            echo "<strong>$p</strong> ";
        } else {
            echo "<a href='?page=$p'>$p</a> ";
        }
    }

    // Nút trang sau
    if ($page < $total_pages) {
        $next_page = $page + 1;
        echo "<a href='?page=$next_page'>Trang sau</a>";
    }

    echo "</div>";
    ?>
</body>

</html>