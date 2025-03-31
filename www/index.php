<?php
session_start();
require_once 'db_connect.php';
// Store PDO in a global variable for controllers to use.
$GLOBALS['pdo'] = $pdo;
require 'routes.php';
?>
