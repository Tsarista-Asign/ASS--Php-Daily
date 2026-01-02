<?php
include '../config/mysql_config.php';

echo "
<div style='margin-bottom:15px;'>
    <a href='table_create.php'><button>Tạo bảng</button></a>
    <a href='data_insert.php'><button>Thêm dữ liệu</button></a>
    <a href='data_update.php'><button>Cập nhật dữ liệu</button></a>
    <a href='data_delete.php'><button>Xóa 1 bản ghi</button></a>
    <a href='table_delete.php'><button>Xóa bảng</button></a>
</div>
";

// Hiển thị danh sách nếu bảng còn tồn tại
$sql = "SHOW TABLES LIKE 'MyGuests'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $sql = "SELECT id, firstname, lastname, reg_date FROM MyGuests";
    $result = $conn->query($sql);

    echo "<h2>Danh sách nhân viên</h2>";
    echo "<table border='1' cellpadding='5'>
    <tr><th>Id</th><th>Firstname</th><th>Lastname</th><th>Reg_Date</th></tr>";

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['firstname']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['reg_date']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>0 results</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p><b>Bảng MyGuests chưa tồn tại hoặc đã bị xóa.</b></p>";
}

$conn->close();
?>
