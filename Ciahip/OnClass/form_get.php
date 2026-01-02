<?php
if (isset($_GET['ten_sach'], $_GET['tac_gia'], $_GET['nxb'], $_GET['nam'])) {
    $ten_sach = htmlspecialchars($_GET['ten_sach']);
    $tac_gia  = htmlspecialchars($_GET['tac_gia']);
    $nxb      = htmlspecialchars($_GET['nxb']);
    $nam      = htmlspecialchars($_GET['nam']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form GET</title>
    <style>
        body { font-family: Time New Roman, sans-serif; background: #ffffffff; text-align: center; }
        form { margin: 40px auto; padding: 20px; width: 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);}
        input[type="text"], input[type="number"] { width: 90%; padding: 8px; margin: 8px 0; border-radius: 8px; border: 1px solid #ccc;}
        input[type="submit"] { background: #0f1b26; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;}
        input[type="submit"]:hover { background: #2c1800; }
        .back { display: inline-block; margin-top: 15px; padding: 8px 16px; background: #908277; color: #fff; border-radius: 8px; text-decoration: none;}
        .back:hover { background: #6e5c4e; }
    </style>
</head>
<body>
    <h2>NHẬP THÔNG TIN SÁCH (GET)</h2>
    <?php if (!empty($ten_sach) && !empty($tac_gia) && !empty($nxb) && !empty($nam)): ?>
        <div style="background:#fff; padding:20px; border-radius:12px; width:300px; margin:20px auto; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
            <h3>KẾT QUẢ:</h3>
            <p><b>Tên sách:</b> <?= $ten_sach ?></p>
            <p><b>Tác giả:</b> <?= $tac_gia ?></p>
            <p><b>Nhà xuất bản:</b> <?= $nxb ?></p>
            <p><b>Năm xuất bản:</b> <?= $nam ?></p>
        </div>
    <?php endif; ?>

    <form method="get" action="form_get.php">
        <input type="text" name="ten_sach" placeholder="Tên sách" required>
        <input type="text" name="tac_gia" placeholder="Tác giả" required>
        <input type="text" name="nxb" placeholder="Nhà xuất bản" required>
        <input type="number" name="nam" placeholder="Năm xuất bản" required>
        <input type="submit" value="Gửi">
    </form>
    <a href="index.php" class="back">⬅ Quay lại Trang chủ</a>
</body>
</html>
