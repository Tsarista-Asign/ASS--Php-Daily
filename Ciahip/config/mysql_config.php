<?php
// Config Local
// $servername = "localhost";
// $dbname = "ass";
// $username = "root";
// $password = "170105";

// Config InfinityFree
$servername = "sql206.infinityfree.com";
$dbname     = "if0_39714427_btvn";
$username   = "if0_39714427";
$password   = "Chia1203";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
