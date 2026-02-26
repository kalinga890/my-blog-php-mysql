<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
include '../config/db.php';

$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = $conn->real_escape_string($_POST['title']);
    $content  = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);
    $image    = '';

    if (!empty($_FILES['image']['name'])) {
        $ext   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = time() . '_' . rand(100,999) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image");
    }

    $conn->query("INSERT INTO posts (title, content, image, category)
                  VALUES ('$title', '$content', '$image', '$category')");
    $success = "✅ Post published successfully!";
}

include '../includes/header.php';
?>

<div class="container">
  <h2>✍️ Create New Post</h2>

  <?php if($success): ?>
    <div class="success">
      <?= $success ?>
      <a href="dashboard.php" style="color:white;margin-left:10px">← Back to Dashboard</a>
    </div>
  <?php endif; ?>

  <div class="card">
    <form method="POST" enctype="multipart/form-data">

      <label><strong>Post Title</strong></label>
      <input type="text" name="title" placeholder="Enter post title..." required>

      <label><strong>Category</strong></label>
      <select name="category"
              style="width:100%;padding:10px;margin:8px 0;
                     border:1px solid #ccc;border-radius:5px;font-size:14px">
        <?php while($cat = $categories->fetch_assoc()): ?>
          <option value="<?= $cat['name'] ?>"><?= $cat['name'] ?></option>
        <?php endwhile; ?>
      </select>

      <label><strong>Content</strong></label>
      <textarea name="content" rows="12"
                placeholder="Write your post content here..."
                style="height:250px" required></textarea>

      <label><strong>Featured Image (optional)</strong></label>
      <input type="file" name="image" accept="image/*">
      <br><br>

      <button type="submit">🚀 Publish Post</button>
      <a href="dashboard.php" class="btn"
         style="background:#999;margin-left:10px">Cancel</a>

    </form>
  </div>
</div>

<?php include '../includes/footer.php'; ?>