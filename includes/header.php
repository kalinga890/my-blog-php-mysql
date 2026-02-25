<?php if(!isset($sessionStarted)){ $sessionStarted=true; if(session_status() == PHP_SESSION_NONE){ session_start(); } } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog</title>
  <link rel="stylesheet" href="/my_blog/style.css">
</head>
<body>
<nav>
  <a href="/my_blog/index.php">🏠 Home</a>
  <?php if(isset($_SESSION['admin'])): ?>
    <a href="/my_blog/admin/dashboard.php">Dashboard</a>
    <a href="/my_blog/admin/logout.php">Logout</a>
  <?php endif; ?>
</nav>