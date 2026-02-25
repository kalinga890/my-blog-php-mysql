<footer>
  <div style="display:flex; justify-content:space-between; align-items:flex-start; 
              max-width:900px; margin:0 auto; padding:30px 20px; gap:30px; 
              flex-wrap:wrap;">
    
    <div>
      <h3 style="color:white; font-size:20px; margin-bottom:8px;">📝 My Blog</h3>
      <p style="color:#bdc3c7; font-size:14px;">A simple blog built with PHP & MySQL</p>
    </div>

    <div>
      <h4 style="color:white; margin-bottom:10px;">Quick Links</h4>
      <a href="/my_blog/index.php" 
         style="display:block; color:#bdc3c7; text-decoration:none; margin-bottom:6px; font-size:14px;">
         🏠 Home
      </a>
      <a href="/my_blog/admin/login.php" 
         style="display:block; color:#bdc3c7; text-decoration:none; margin-bottom:6px; font-size:14px;">
         🔐 Admin
      </a>
    </div>

    <div>
      <h4 style="color:white; margin-bottom:10px;">Built With</h4>
      <p style="color:#bdc3c7; font-size:14px; margin-bottom:5px;">⚙️ PHP</p>
      <p style="color:#bdc3c7; font-size:14px; margin-bottom:5px;">🗄️ MySQL</p>
      <p style="color:#bdc3c7; font-size:14px; margin-bottom:5px;">🎨 CSS</p>
    </div>

  </div>

  <div style="border-top:1px solid #34495e; text-align:center; 
              padding:15px 20px; color:#bdc3c7; font-size:13px;">
    &copy; <?= date('Y') ?> My Blog. All rights reserved.
  </div>
</footer>
</body>
</html>
