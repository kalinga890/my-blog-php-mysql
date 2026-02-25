<?php
include 'includes/header.php';
include 'config/db.php';

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<div class="container">
  <h1>📝 Latest Posts</h1>

  <?php if($result->num_rows === 0): ?>
    <div class="card">
      <p>No posts yet. <a href="admin/login.php">Admin can login</a> to create posts.</p>
    </div>
  <?php endif; ?>

  <?php while($post = $result->fetch_assoc()): ?>
    <div class="card">
      <?php if($post['image']): ?>
        <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
      <?php endif; ?>
      <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
      <p><?= substr(strip_tags($post['content']), 0, 150) ?>...</p>
      <small>📅 <?= $post['created_at'] ?></small><br><br>
      <a href="post.php?id=<?= $post['id'] ?>" class="btn">Read More →</a>
    </div>
  <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>