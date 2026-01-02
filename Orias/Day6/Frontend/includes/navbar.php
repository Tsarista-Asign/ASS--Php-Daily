<?php
$cu = current_user();
?>
<link rel="stylesheet" href="assets/css/navbar.css">

<style>
  .navbar {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 10px;
    /* background: #24292f; */
    border: 1px solid #cececeff;
    color: #fff;
    border-radius: 8px;
    margin-bottom: 12px;
    font-family: Arial, sans-serif;
  }

  .nav-item {
    color: #2e2e2eff;
    text-decoration: none;  
    transition: 0.2s;
  }

  .nav-item:hover {
    color: #58a6ff;
  }

  .nav-spacer {
    margin-left: auto;
  }

  .nav-user {
    color: #1d5cb9ff;
  }

  .logout {
    margin-left: 10px;
    color: #e71010ff;
  }

  .logout:hover {
    color: #f85149;
  }
</style>

<div class="navbar">
  <a href="index.php" class="nav-item">Home</a>
  <a href="listdepartments.php" class="nav-item">Departments</a>
  <a href="listroles.php" class="nav-item">Roles</a>
  <a href="listemployees.php" class="nav-item">Employees</a>

  <?php if (is_admin()): ?>
    <a href="users.php" class="nav-item">Users</a>
  <?php endif; ?>

  <div class="nav-spacer"></div>

  <?php if ($cu['username']): ?>
    <span class="nav-user">
      👤 <?= safe_html($cu['username']) ?> (<?= safe_html($cu['auth']) ?>)
    </span>
    <a href="logout.php" class="nav-item logout">Log out</a>
  <?php endif; ?>
</div>
