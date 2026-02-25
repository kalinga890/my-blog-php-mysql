<?php
session_start();
if(isset($_SESSION['admin'])) { header("Location: dashboard.php"); exit; }
include '../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $result   = $conn->query("SELECT * FROM users WHERE username='$username'")->fetch_assoc();

    if ($result && password_verify($_POST['password'], $result['password'])) {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="/my_blog/style.css">
</head>
<body>
<div class="container" style="max-width:400px;margin-top:80px">
  <div class="card">
    <h2 style="text-align:center">🔐 Admin Login</h2>
    <br>
    <?php if($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" placeholder="Enter username" required>
      <label>Password</label>
      <input type="password" name="password" placeholder="Enter password" required>
      <br>
      <button type="submit" style="width:100%">Login →</button>
    </form>
  </div>
</div>
</body>
</html>