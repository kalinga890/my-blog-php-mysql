<?php
include 'includes/header.php';
include 'config/db.php';

// Search & Category filter
$search   = isset($_GET['search'])   ? $conn->real_escape_string($_GET['search'])   : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$where = "WHERE 1=1";
if ($search)   $where .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
if ($category) $where .= " AND category='$category'";

$result     = $conn->query("SELECT * FROM posts $where ORDER BY created_at DESC");
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>

<div class="container">
  <h1>📝 Latest Posts</h1>

  <!-- Search & Filter Bar -->
  <form method="GET" style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap">
    <input type="text" name="search"
           placeholder="🔍 Search posts..."
           value="<?= htmlspecialchars($search) ?>"
           style="flex:1;min-width:200px">
    <select name="category"
            style="padding:10px;border-radius:5px;border:1px solid #ccc;font-size:14px">
      <option value="">All Categories</option>
      <?php while($cat = $categories->fetch_assoc()): ?>
        <option value="<?= $cat['name'] ?>"
          <?= $category == $cat['name'] ? 'selected' : '' ?>>
          <?= $cat['name'] ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Search</button>
    <?php if($search || $category): ?>
      <a href="index.php" class="btn" style="background:#999">✕ Clear</a>
    <?php endif; ?>
  </form>

  <?php if($result->num_rows === 0): ?>
    <div class="card">
      <p>No posts found. <a href="index.php">View all posts</a></p>
    </div>
  <?php endif; ?>

  <?php while($post = $result->fetch_assoc()): ?>
    <div class="card">
      <?php if($post['image']): ?>
        <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
      <?php endif; ?>

      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;margin-bottom:8px">
        <span style="background:#2c3e50;color:white;padding:3px 12px;
                     border-radius:20px;font-size:12px">
          🏷️ <?= htmlspecialchars($post['category']) ?>
        </span>
        <small style="color:#999">📅 <?= $post['created_at'] ?></small>
      </div>

      <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
      <p><?= substr(strip_tags($post['content']), 0, 150) ?>...</p>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px">
        <div style="color:#999;font-size:13px">
          👁️ <?= $post['views'] ?> views &nbsp;|&nbsp;
          ❤️ <?= $post['likes'] ?> likes
        </div>
        <a href="post.php?id=<?= $post['id'] ?>" class="btn">Read More →</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>