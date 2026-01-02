<?php
// Frontend/includes/navbar.php (giữ nguyên từ navbar.php)
$cu = current_user();
?>
<div style="display:flex;gap:10px;align-items:center;padding:10px;background:#24292f;color:#fff;border-radius:8px;margin-bottom:12px">
  <a href="index.php" style="color:#fff;text-decoration:none">Trang chủ</a>
  <a href="listdepartments.php" style="color:#fff;text-decoration:none">Departments</a>
  <a href="listroles.php" style="color:#fff;text-decoration:none">Roles</a>
  <a href="listemployees.php" style="color:#fff;text-decoration:none">Employees</a>
  <?php if (is_admin()): ?>
    <a href="users.php" style="color:#fff;text-decoration:none">Users</a>
  <?php endif; ?>
  <div style="margin-left:auto"></div>
  <?php if ($cu['username']): ?>
    <span>👤 <?= safe_html($cu['username']) ?> (<?= safe_html($cu['auth']) ?>)</span>
    <a href="logout.php" style="color:#fff;text-decoration:none;margin-left:10px">Đăng xuất</a>
  <?php endif; ?>
</div>