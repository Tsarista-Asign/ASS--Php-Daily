<?php
// Frontend/logout.php
require_once '../Backend/common.php';

session_start();
session_unset();
session_destroy();
header("Location: login.php");
exit;