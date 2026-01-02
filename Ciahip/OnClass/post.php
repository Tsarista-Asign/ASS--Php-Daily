<?php
// post.php
if (isset($_POST['name']) && isset($_POST['age'])) {
    $name = htmlspecialchars($_POST['name']);
    $age = htmlspecialchars($_POST['age']);
    echo "Xin chào, tôi là $name và tôi $age tuổi.";
} else {
    echo '<form method="post" action="post.php">
            Nhập tên: <input type="text" name="name"><br><br>
            Nhập tuổi: <input type="number" name="age"><br><br>
            <input type="submit" value="Gửi">
          </form>';
}
?>
