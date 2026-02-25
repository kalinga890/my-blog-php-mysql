<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config/db.php';
include '../includes/header.php';

$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
$totalPosts    = $conn->query("SELECT COUNT(*) as c FROM posts")->fetch_assoc()['c'];
$totalComments = $conn->query("SELECT COUNT(*) as c FROM comments")->fetch_assoc()['c'];
?>

<div class="container">
  <h1>📊 Admin Dashboard</h1>

  <!-- Stats -->
  <div style="display:flex;gap:20px;margin-bottom:20px">
    <div class="card" style="flex:1;text-align:center">
      <h2 style="font-size:40px;color:#f39c12"><?= $totalPosts ?></h2>
      <p>Total Posts</p>
    </div>
    <div class="card" style="flex:1;text-align:center">
      <h2 style="font-size:40px;color:#2ecc71"><?= $totalComments ?></h2>
      <p>Total Comments</p>
    </div>
  </div>

  <a href="create_post.php" class="btn">+ New Post</a>
  <br>

  <table>
    <tr>
      <th>#</th>
      <th>Title</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php $i=1; while($p = $posts->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><?= $p['created_at'] ?></td>
      <td>
        <a href="../post.php?id=<?= $p['id'] ?>" class="btn" style="background:#2ecc71">👁 View</a>
        <a href="edit_post.php?id=<?= $p['id'] ?>" class="btn" style="background:#3498db">✏️ Edit</a>
        <a href="delete_post.php?id=<?= $p['id'] ?>" class="btn" style="background:#e74c3c"
           onclick="return confirm('Are you sure you want to delete this post?')">🗑 Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php include '../includes/footer.php'; ?>