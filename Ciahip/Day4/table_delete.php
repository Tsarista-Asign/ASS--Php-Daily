<?php
include '../config/mysql_config.php';

$sql = "DROP TABLE IF EXISTS MyGuests";
$conn->query($sql);

$conn->close();

header("Location: list_data.php");
exit;
?>
