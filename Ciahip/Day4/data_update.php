<?php
include '../config/mysql_config.php';

$sql = "UPDATE MyGuests SET firstname='Jane' WHERE firstname='James'";
$conn->query($sql);

$conn->close();

header("Location: list_data.php");
exit;
?>
