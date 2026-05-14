<?php session_start();

/* ── Simple admin credential check ──────────────────────────────────────── */
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'ymk@2024');          // change before production

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    $errors = [];

    if (!$u) $errors[] = 'Username is required.';
    if (!$p) $errors[] = 'Password is required.';

    if (empty($errors)) {
        if ($u === ADMIN_USER && $p === ADMIN_PASS) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin_dashboard.php'); exit;
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK – Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      background: linear-gradient(160deg,#f8f0fb 0%,#e8f4ff 50%,#fff8e7 100%);
      font-family:'Poppins',sans-serif;
      display:flex;align-items:center;justify-content:center;
    }
    .container{
      background:#fff;
      border-radius:28px;
      box-shadow:0 10px 50px rgba(161,140,209,0.18);
      padding:50px 55px;
      width:100%;max-width:480px;
      animation:slideUp 0.7s ease;
    }
    @keyframes slideUp{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}

    .logo-top{text-align:center;font-size:2.5rem;margin-bottom:10px;}
    h2{
      font-family:'Playfair Display',serif;
      text-align:center;color:#2d1b69;font-size:1.8rem;margin-bottom:4px;
    }
    .sub{text-align:center;color:#a18cd1;font-size:0.88rem;margin-bottom:30px;letter-spacing:1px;}

    .admin-badge{
      display:flex;align-items:center;justify-content:center;gap:8px;
      background:linear-gradient(135deg,#2d1b69,#5a3e8a);
      color:#fff;border-radius:50px;padding:6px 20px;
      font-size:0.8rem;font-weight:600;letter-spacing:1px;
      width:fit-content;margin:0 auto 24px;
    }

    .field-group{margin-bottom:18px;}
    label{
      display:block;font-size:0.8rem;font-weight:600;
      color:#5a3e8a;margin-bottom:6px;letter-spacing:1px;text-transform:uppercase;
    }
    input{
      width:100%;padding:12px 16px;
      border:2px solid #e8d5f5;border-radius:12px;
      font-family:'Poppins',sans-serif;font-size:0.95rem;color:#333;
      outline:none;transition:border 0.3s;
      background:#fdfaff;
    }
    input:focus{border-color:#a18cd1;background:#fff;}
    .icon-label{display:flex;align-items:center;gap:8px;}

    .btn-submit{
      width:100%;padding:14px;border:none;
      background:linear-gradient(135deg,#2d1b69,#a18cd1);
      color:#fff;font-size:1rem;font-weight:600;
      border-radius:14px;cursor:pointer;margin-top:10px;
      letter-spacing:1px;transition:transform 0.2s,box-shadow 0.2s;
      font-family:'Poppins',sans-serif;
    }
    .btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(45,27,105,0.35);}

    .error{color:#e74c3c;font-size:0.82rem;margin-top:4px;}

    .back-link{
      display:block;text-align:center;margin-top:20px;
      color:#a18cd1;font-size:0.84rem;text-decoration:none;
      transition:color 0.2s;
    }
    .back-link:hover{color:#5a3e8a;}

    .hint{
      background:#f0f4ff;border-left:4px solid #a18cd1;
      padding:10px 14px;border-radius:8px;margin-bottom:22px;
      font-size:0.8rem;color:#5a3e8a;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="logo-top">🛡️</div>
  <h2>Admin Portal</h2>
  <p class="sub">YMK EVENT PLANNER — ADMIN ACCESS</p>
  <div class="admin-badge">🔐 RESTRICTED ACCESS</div>

  <div class="hint">
    Default credentials — <strong>admin</strong> / <strong>ymk@2024</strong>
  </div>

  <?php if (!empty($errors)): ?>
    <div style="background:#fff0f0;border-left:4px solid #e74c3c;padding:12px;border-radius:8px;margin-bottom:16px;">
      <?php foreach ($errors as $e): ?>
        <p class="error">⚠ <?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="admin_login.php">
    <div class="field-group">
      <label><span class="icon-label">👤 Username</span></label>
      <input type="text" name="username" placeholder="Admin username"
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="field-group">
      <label><span class="icon-label">🔒 Password</span></label>
      <input type="password" name="password" placeholder="Admin password">
    </div>
    <button type="submit" class="btn-submit">🔓 Login to Dashboard</button>
  </form>

  <a href="index.php" class="back-link">← Back to YMK Event Planner</a>
</div>
</body>
</html>
