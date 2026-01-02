<?php
// get.php
if (isset($_GET['name']) && isset($_GET['age'])) {
    $name = htmlspecialchars($_GET['name']);
    $age = htmlspecialchars($_GET['age']);
    echo "Xin chào, tôi là $name và tôi $age tuổi.";
} else {
    echo '<form method="get" action="get.php">
            Nhập tên: <input type="text" name="name"><br><br>
            Nhập tuổi: <input type="number" name="age"><br><br>
            <input type="submit" value="Gửi">
          </form>';
}
?>
