<?php
include 'includes/header.php';
include 'config/db.php';

$id   = intval($_GET['id']);
$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();

if (!$post) {
    echo "<div class='container'><p>Post not found. <a href='index.php'>Go Home</a></p></div>";
    include 'includes/footer.php';
    exit;
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $conn->real_escape_string($_POST['name']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $conn->query("INSERT INTO comments (post_id, name, comment) VALUES ($id, '$name', '$comment')");
    header("Location: post.php?id=$id");
    exit;
}

$comments = $conn->query("SELECT * FROM comments WHERE post_id=$id ORDER BY created_at DESC");
?>

<div class="container">
  <?php if($post['image']): ?>
    <img src="uploads/<?= htmlspecialchars($post['image']) ?>" 
         style="width:100%;max-height:400px;object-fit:cover;border-radius:8px;margin-bottom:20px">
  <?php endif; ?>

  <h1><?= htmlspecialchars($post['title']) ?></h1>
  <small>📅 <?= $post['created_at'] ?></small>
  <hr>
  <div class="content" style="line-height:1.8;font-size:16px">
    <?= nl2br(htmlspecialchars($post['content'])) ?>
  </div>

  <hr>
  <h3>💬 Comments (<?= $comments->num_rows ?>)</h3>

  <?php if($comments->num_rows === 0): ?>
    <p style="color:#999">No comments yet. Be the first!</p>
  <?php endif; ?>

  <?php while($c = $comments->fetch_assoc()): ?>
    <div class="comment">
      <strong>👤 <?= htmlspecialchars($c['name']) ?></strong>
      <p><?= htmlspecialchars($c['comment']) ?></p>
      <small>📅 <?= $c['created_at'] ?></small>
    </div>
  <?php endwhile; ?>

  <hr>
  <h3>✍️ Leave a Comment</h3>
  <form method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <textarea name="comment" placeholder="Write your comment here..." required></textarea>
    <button type="submit">Post Comment</button>
  </form>

  <br>
  <a href="index.php" class="btn">← Back to Home</a>
</div>

<?php include 'includes/footer.php'; ?>