<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config/db.php';

$id   = intval($_GET['id'] ?? 0);
$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();

if (!$post) { header("Location: dashboard.php"); exit; }

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $image   = $post['image'];

    if (!empty($_FILES['image']['name'])) {
        $ext   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = time() . '_' . rand(100,999) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    $conn->query("UPDATE posts SET title='$title', content='$content', image='$image' WHERE id=$id");
    $success = "✅ Post updated successfully!";
    $post    = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
}

include '../includes/header.php';
?>

<div class="container">
  <h2>✏️ Edit Post</h2>
  <?php if($success): ?>
    <div class="success"><?= $success ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="POST" enctype="multipart/form-data">
      <label><strong>Post Title</strong></label>
      <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

      <label><strong>Content</strong></label>
      <textarea name="content" rows="12" style="height:250px" required><?= htmlspecialchars($post['content']) ?></textarea>

      <?php if($post['image']): ?>
        <label><strong>Current Image:</strong></label><br>
        <img src="../uploads/<?= $post['image'] ?>" style="max-height:150px;border-radius:5px;margin:10px 0"><br>
      <?php endif; ?>

      <label><strong>Change Image (optional)</strong></label>
      <input type="file" name="image" accept="image/*">
      <br><br>

      <button type="submit">💾 Update Post</button>
      <a href="dashboard.php" class="btn" style="background:#999;margin-left:10px">Cancel</a>
    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>