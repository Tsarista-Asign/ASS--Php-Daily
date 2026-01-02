<?php
// Frontend/students_delete.php (fallback)
require_once '../backend/common.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    delete_student($id);
}
header("Location: index.php");
exit;