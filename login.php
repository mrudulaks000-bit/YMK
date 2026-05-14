<?php
session_start();

// Already logged in → go straight to events
if (!empty($_SESSION['user'])) {
    header('Location: events.php'); exit;
}

$usersFile = __DIR__ . '/registered_users.json';

function loadUsers($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?: [];
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username) $errors[] = 'Username is required.';
    if (!$password) $errors[] = 'Password is required.';

    if (empty($errors)) {
        $users = loadUsers($usersFile);
        $found = null;
        foreach ($users as $u) {
            if (strtolower($u['username'] ?? '') === strtolower($username)) {
                $found = $u; break;
            }
        }
        if (!$found || !password_verify($password, $found['password'] ?? '')) {
            $errors[] = 'Invalid username or password.';
        } else {
            $_SESSION['user'] = [
                'name'       => $found['name'],
                'username'   => $found['username'],
                'address'    => $found['address'],
                'phone'      => $found['phone'],
                'email'      => $found['email'],
                'event_date' => $found['event_date'],
            ];
            header('Location: events.php'); exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK - Login</title>
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
      width:100%;max-width:460px;
      animation:slideUp 0.7s ease;
    }
    @keyframes slideUp{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
    .logo-top{text-align:center;font-size:2.5rem;margin-bottom:10px;}
    h2{font-family:'Playfair Display',serif;text-align:center;color:#2d1b69;font-size:1.8rem;margin-bottom:4px;}
    .sub{text-align:center;color:#a18cd1;font-size:0.88rem;margin-bottom:30px;letter-spacing:1px;}
    .field-group{margin-bottom:18px;}
    label{display:block;font-size:0.8rem;font-weight:600;color:#5a3e8a;margin-bottom:6px;letter-spacing:1px;text-transform:uppercase;}
    input{width:100%;padding:12px 16px;border:2px solid #e8d5f5;border-radius:12px;font-family:'Poppins',sans-serif;font-size:0.95rem;color:#333;outline:none;transition:border 0.3s;background:#fdfaff;}
    input:focus{border-color:#a18cd1;background:#fff;}
    .icon-label{display:flex;align-items:center;gap:8px;}
    .btn-submit{width:100%;padding:14px;border:none;background:linear-gradient(135deg,#ff6eb4,#a18cd1);color:#fff;font-size:1rem;font-weight:600;border-radius:14px;cursor:pointer;margin-top:10px;letter-spacing:1px;transition:transform 0.2s,box-shadow 0.2s;font-family:'Poppins',sans-serif;}
    .btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(161,140,209,0.4);}
    .error{color:#e74c3c;font-size:0.82rem;margin-top:4px;}
    .error-box{background:#fff0f0;border-left:4px solid #e74c3c;padding:12px;border-radius:8px;margin-bottom:16px;}
    .register-link{text-align:center;margin-top:22px;font-size:0.85rem;color:#888;}
    .register-link a{color:#a18cd1;font-weight:600;text-decoration:none;transition:color 0.2s;}
    .register-link a:hover{color:#5a3e8a;}
    .divider{display:flex;align-items:center;gap:12px;margin:22px 0;}
    .divider hr{flex:1;border:none;border-top:1.5px solid #f0e8fa;}
    .divider span{color:#c4a8e0;font-size:0.8rem;}
  </style>
</head>
<body>
<div class="container">
  <div class="logo-top">🎊</div>
  <h2>Welcome Back!</h2>
  <p class="sub">LOGIN TO YOUR ACCOUNT</p>

  <?php if (!empty($errors)): ?>
    <div class="error-box">
      <?php foreach ($errors as $e): ?>
        <p class="error">⚠ <?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <div class="field-group">
      <label><span class="icon-label">🆔 Username</span></label>
      <input type="text" name="username" placeholder="Enter your username"
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="field-group">
      <label><span class="icon-label">🔒 Password</span></label>
      <input type="password" name="password" placeholder="Enter your password">
    </div>
    <button type="submit" class="btn-submit">✨ Login</button>
  </form>

  <div class="divider"><hr><span>OR</span><hr></div>
  <p class="register-link">Don't have an account? <a href="register.php">Register here &rarr;</a></p>
</div>
</body>
</html>
