<?php
$host = getenv('MYSQLHOST')     ?: 'railway.internal';
$port = getenv('MYSQLPORT')     ?: '3306';
$user = getenv('MYSQLUSER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'yaLKamFZqIBFRxySxdWFVMHqSUWLOZKz';
$db   = getenv('MYSQLDATABASE') ?: 'railway';

$conn = new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>