<?php
include 'includes/header.php';
include 'config/db.php';

$id = intval($_GET['id']);

// Increment view counter
$conn->query("UPDATE posts SET views = views + 1 WHERE id=$id");

// Handle like
if (isset($_GET['like']) && $_GET['like'] == 1) {
    $conn->query("UPDATE posts SET likes = likes + 1 WHERE id=$id");
    header("Location: post.php?id=$id");
    exit;
}

// Handle comment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $conn->real_escape_string($_POST['name']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $conn->query("INSERT INTO comments (post_id, name, comment) VALUES ($id, '$name', '$comment')");
    header("Location: post.php?id=$id");
    exit;
}

$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();

if (!$post) {
    echo "<div class='container'><p>Post not found. <a href='index.php'>Go Home</a></p></div>";
    include 'includes/footer.php';
    exit;
}

$comments = $conn->query("SELECT * FROM comments WHERE post_id=$id ORDER BY created_at DESC");
?>

<div class="container">
  <?php if($post['image']): ?>
    <img src="uploads/<?= htmlspecialchars($post['image']) ?>"
         style="width:100%;max-height:400px;object-fit:cover;border-radius:8px;margin-bottom:20px">
  <?php endif; ?>

  <!-- Category Badge -->
  <span style="background:#2c3e50;color:white;padding:4px 14px;
               border-radius:20px;font-size:13px">
    🏷️ <?= htmlspecialchars($post['category']) ?>
  </span>

  <h1 style="margin-top:15px"><?= htmlspecialchars($post['title']) ?></h1>
  <small>📅 <?= $post['created_at'] ?></small>

  <!-- Stats Bar -->
  <div style="display:flex;gap:20px;margin:15px 0;padding:12px 15px;
              background:#ecf0f1;border-radius:8px;flex-wrap:wrap">
    <span>👁️ <strong><?= $post['views'] ?></strong> Views</span>
    <span>❤️ <strong><?= $post['likes'] ?></strong> Likes</span>
    <span>💬 <strong><?= $comments->num_rows ?></strong> Comments</span>
  </div>

  <hr>

  <!-- Post Content -->
  <div style="line-height:1.8;font-size:16px;margin:20px 0">
    <?= nl2br(htmlspecialchars($post['content'])) ?>
  </div>

  <!-- Like Button -->
  <div style="text-align:center;margin:30px 0">
    <a href="post.php?id=<?= $id ?>&like=1" class="btn"
       style="background:#e74c3c;font-size:16px;padding:12px 30px">
      ❤️ Like this Post (<?= $post['likes'] ?>)
    </a>
  </div>

  <hr>

  <!-- Comments Section -->
  <h3>💬 Comments (<?= $comments->num_rows ?>)</h3>

  <?php if($comments->num_rows === 0): ?>
    <p style="color:#999;margin-bottom:15px">No comments yet. Be the first!</p>
  <?php endif; ?>

  <?php while($c = $comments->fetch_assoc()): ?>
    <div class="comment">
      <strong>👤 <?= htmlspecialchars($c['name']) ?></strong>
      <p><?= htmlspecialchars($c['comment']) ?></p>
      <small>📅 <?= $c['created_at'] ?></small>
    </div>
  <?php endwhile; ?>

  <hr>

  <!-- Comment Form -->
  <h3>✍️ Leave a Comment</h3>
  <form method="POST">
    <input type="text" name="name" placeholder="Your Name" required>
    <textarea name="comment" placeholder="Write your comment here..." required></textarea>
    <button type="submit">💬 Post Comment</button>
  </form>

  <br>
  <a href="index.php" class="btn" style="background:#999">← Back to Home</a>
</div>

<?php include 'includes/footer.php'; ?>