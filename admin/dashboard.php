<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config/db.php';

// Delete comment
if (isset($_GET['delete_comment'])) {
    $cid = intval($_GET['delete_comment']);
    $conn->query("DELETE FROM comments WHERE id=$cid");
    header("Location: dashboard.php");
    exit;
}

// Delete post
if (isset($_GET['delete_post'])) {
    $pid = intval($_GET['delete_post']);
    $conn->query("DELETE FROM posts WHERE id=$pid");
    header("Location: dashboard.php");
    exit;
}

$posts         = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
$totalPosts    = $conn->query("SELECT COUNT(*) as c FROM posts")->fetch_assoc()['c'];
$totalComments = $conn->query("SELECT COUNT(*) as c FROM comments")->fetch_assoc()['c'];
$totalLikes    = $conn->query("SELECT SUM(likes) as c FROM posts")->fetch_assoc()['c'] ?? 0;
$totalViews    = $conn->query("SELECT SUM(views) as c FROM posts")->fetch_assoc()['c'] ?? 0;
$comments      = $conn->query("
    SELECT c.*, p.title as post_title 
    FROM comments c 
    JOIN posts p ON c.post_id = p.id 
    ORDER BY c.created_at DESC
");

include '../includes/header.php';
?>

<div class="container">
  <h1>📊 Admin Dashboard</h1>

  <!-- Stats Cards -->
  <div style="display:flex;gap:15px;margin-bottom:25px;flex-wrap:wrap">
    <div class="card" style="flex:1;text-align:center;min-width:120px">
      <h2 style="font-size:35px;color:#f39c12"><?= $totalPosts ?></h2>
      <p>📝 Posts</p>
    </div>
    <div class="card" style="flex:1;text-align:center;min-width:120px">
      <h2 style="font-size:35px;color:#2ecc71"><?= $totalComments ?></h2>
      <p>💬 Comments</p>
    </div>
    <div class="card" style="flex:1;text-align:center;min-width:120px">
      <h2 style="font-size:35px;color:#e74c3c"><?= $totalLikes ?></h2>
      <p>❤️ Likes</p>
    </div>
    <div class="card" style="flex:1;text-align:center;min-width:120px">
      <h2 style="font-size:35px;color:#3498db"><?= $totalViews ?></h2>
      <p>👁️ Views</p>
    </div>
  </div>

  <a href="create_post.php" class="btn">+ New Post</a>
  <br><br>

  <!-- Posts Table -->
  <h3>📝 All Posts</h3>
  <table>
    <tr>
      <th>#</th>
      <th>Title</th>
      <th>Category</th>
      <th>Views</th>
      <th>Likes</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php $i=1; while($p = $posts->fetch_assoc()): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td>
        <span style="background:#2c3e50;color:white;padding:2px 8px;
                     border-radius:10px;font-size:11px">
          <?= $p['category'] ?>
        </span>
      </td>
      <td>👁️ <?= $p['views'] ?></td>
      <td>❤️ <?= $p['likes'] ?></td>
      <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
      <td>
        <a href="../post.php?id=<?= $p['id'] ?>" class="btn"
           style="background:#2ecc71;padding:5px 8px;font-size:12px">👁</a>
        <a href="edit_post.php?id=<?= $p['id'] ?>" class="btn"
           style="background:#3498db;padding:5px 8px;font-size:12px">✏️</a>
        <a href="dashboard.php?delete_post=<?= $p['id'] ?>" class="btn"
           style="background:#e74c3c;padding:5px 8px;font-size:12px"
           onclick="return confirm('Delete this post?')">🗑</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <br><br>

  <!-- Comments Table -->
  <h3>💬 All Comments</h3>
  <?php if($comments->num_rows === 0): ?>
    <p style="color:#999">No comments yet.</p>
  <?php else: ?>
  <table>
    <tr>
      <th>Name</th>
      <th>Comment</th>
      <th>Post</th>
      <th>Date</th>
      <th>Action</th>
    </tr>
    <?php while($c = $comments->fetch_assoc()): ?>
    <tr>
      <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
      <td><?= substr(htmlspecialchars($c['comment']), 0, 40) ?>...</td>
      <td><?= htmlspecialchars($c['post_title']) ?></td>
      <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
      <td>
        <a href="dashboard.php?delete_comment=<?= $c['id'] ?>" class="btn"
           style="background:#e74c3c;padding:5px 10px;font-size:12px"
           onclick="return confirm('Delete this comment?')">🗑 Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
  <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>