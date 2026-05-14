<?php
session_start();

$usersFile = __DIR__ . '/registered_users.json';

function loadUsers($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?: [];
}
function saveUsers($file, $users) {
    file_put_contents($file, json_encode(array_values($users), JSON_PRETTY_PRINT));
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']       ?? '');
    $address  = trim($_POST['address']    ?? '');
    $phone    = trim($_POST['phone']      ?? '');
    $email    = trim($_POST['email']      ?? '');
    $username = trim($_POST['username']   ?? '');
    $password = trim($_POST['password']   ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');
    $date     = trim($_POST['event_date'] ?? '');

    if (!$name)                                        $errors[] = 'Full name is required.';
    if (!$address)                                     $errors[] = 'Address is required.';
    if (!preg_match('/^[0-9]{10}$/', $phone))          $errors[] = 'Enter a valid 10-digit phone number.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errors[] = 'Enter a valid email address.';
    if (strlen($username) < 4)                         $errors[] = 'Username must be at least 4 characters.';
    if (strlen($password) < 6)                         $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)                        $errors[] = 'Passwords do not match.';
    if (!$date)                                        $errors[] = 'Event date is required.';

    if (empty($errors)) {
        $users = loadUsers($usersFile);

        // Check duplicate username or email
        foreach ($users as $u) {
            if (strtolower($u['username'] ?? '') === strtolower($username)) {
                $errors[] = 'Username already taken. Please choose another.';
                break;
            }
            if (strtolower($u['email'] ?? '') === strtolower($email)) {
                $errors[] = 'An account with this email already exists.';
                break;
            }
        }
    }

    if (empty($errors)) {
        $newUser = [
            'name'             => $name,
            'username'         => $username,
            'password'         => password_hash($password, PASSWORD_DEFAULT),
            'address'          => $address,
            'phone'            => $phone,
            'email'            => $email,
            'event_date'       => $date,
            'selected_event'   => '',
            'selected_package' => '',
            'price'            => '',
            'registered_at'    => date('Y-m-d H:i:s'),
        ];
        $users = loadUsers($usersFile);
        $users[] = $newUser;
        saveUsers($usersFile, $users);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK – Register</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      background: linear-gradient(160deg,#f8f0fb 0%,#e8f4ff 50%,#fff8e7 100%);
      font-family:'Poppins',sans-serif;
      display:flex;align-items:center;justify-content:center;
      padding:30px 20px;
    }
    .container{
      background:#fff;
      border-radius:28px;
      box-shadow:0 10px 50px rgba(161,140,209,0.18);
      padding:50px 55px;
      width:100%;max-width:500px;
      animation:slideUp 0.7s ease;
    }
    @keyframes slideUp{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}

    .logo-top{text-align:center;font-size:2.5rem;margin-bottom:10px;}
    h2{
      font-family:'Playfair Display',serif;
      text-align:center;color:#2d1b69;font-size:1.8rem;margin-bottom:4px;
    }
    .sub{text-align:center;color:#a18cd1;font-size:0.88rem;margin-bottom:30px;letter-spacing:1px;}

    /* Section divider */
    .section-label{
      font-size:0.72rem;font-weight:600;color:#a18cd1;
      letter-spacing:2px;text-transform:uppercase;
      border-bottom:2px solid #f0e8fa;padding-bottom:6px;
      margin:22px 0 16px;
    }

    .field-group{margin-bottom:16px;}
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

    .two-col{display:grid;grid-template-columns:1fr 1fr;gap:14px;}

    .btn-submit{
      width:100%;padding:14px;border:none;
      background:linear-gradient(135deg,#ff6eb4,#a18cd1);
      color:#fff;font-size:1rem;font-weight:600;
      border-radius:14px;cursor:pointer;margin-top:14px;
      letter-spacing:1px;transition:transform 0.2s,box-shadow 0.2s;
      font-family:'Poppins',sans-serif;
    }
    .btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(161,140,209,0.4);}

    .error{color:#e74c3c;font-size:0.82rem;margin-top:4px;}
    .error-box{
      background:#fff0f0;border-left:4px solid #e74c3c;
      padding:12px;border-radius:8px;margin-bottom:16px;
    }

    .login-link{
      text-align:center;margin-top:20px;font-size:0.85rem;color:#888;
    }
    .login-link a{
      color:#a18cd1;font-weight:600;text-decoration:none;
      transition:color 0.2s;
    }
    .login-link a:hover{color:#5a3e8a;}

    /* Success overlay */
    .success-overlay{
      display:none;position:fixed;inset:0;
      background:rgba(45,27,105,0.55);backdrop-filter:blur(4px);
      align-items:center;justify-content:center;z-index:999;
    }
    .success-overlay.show{display:flex;}
    .success-box{
      background:#fff;border-radius:28px;padding:44px 50px;
      text-align:center;max-width:400px;width:90%;
      animation:slideUp 0.5s ease;
      box-shadow:0 20px 60px rgba(45,27,105,0.25);
    }
    .success-box .s-icon{font-size:3.5rem;margin-bottom:16px;display:block;}
    .success-box h3{
      font-family:'Playfair Display',serif;
      color:#2d1b69;font-size:1.6rem;margin-bottom:10px;
    }
    .success-box p{color:#666;font-size:0.9rem;margin-bottom:20px;}
    .btn-go{
      display:inline-block;padding:12px 32px;border:none;
      background:linear-gradient(135deg,#ff6eb4,#a18cd1);
      color:#fff;font-size:0.95rem;font-weight:600;
      border-radius:14px;cursor:pointer;text-decoration:none;
      font-family:'Poppins',sans-serif;letter-spacing:1px;
      transition:transform 0.2s,box-shadow 0.2s;
    }
    .btn-go:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(161,140,209,0.4);}
  </style>
</head>
<body>

<!-- Success overlay -->
<?php if ($success): ?>
<div class="success-overlay show">
  <div class="success-box">
    <span class="s-icon">🎉</span>
    <h3>Registration Successful!</h3>
    <p>Welcome to <strong>YMK Event Planner</strong>!<br>
       Your account has been created. Please log in to continue.</p>
    <a href="login.php" class="btn-go">✨ Go to Login</a>
  </div>
</div>
<?php endif; ?>

<div class="container">
  <div class="logo-top">🎊</div>
  <h2>Create Account</h2>
  <p class="sub">JOIN YMK EVENT PLANNER</p>

  <?php if (!empty($errors)): ?>
    <div class="error-box">
      <?php foreach ($errors as $e): ?>
        <p class="error">⚠ <?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="register.php">

    <p class="section-label">👤 Personal Details</p>

    <div class="field-group">
      <label><span class="icon-label">👤 Full Name</span></label>
      <input type="text" name="name" placeholder="Your full name"
             value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>

    <div class="field-group">
      <label><span class="icon-label">🏠 Address</span></label>
      <input type="text" name="address" placeholder="Your address"
             value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
    </div>

    <div class="two-col">
      <div class="field-group">
        <label><span class="icon-label">📱 Phone</span></label>
        <input type="tel" name="phone" placeholder="10-digit number"
               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
      </div>
      <div class="field-group">
        <label><span class="icon-label">📧 Email</span></label>
        <input type="email" name="email" placeholder="your@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
    </div>

    <div class="field-group">
      <label><span class="icon-label">📅 Event Date</span></label>
      <input type="date" name="event_date"
             value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>">
    </div>

    <p class="section-label">🔐 Login Credentials</p>

    <div class="field-group">
      <label><span class="icon-label">🆔 Username</span></label>
      <input type="text" name="username" placeholder="Choose a username (min 4 chars)"
             value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>

    <div class="two-col">
      <div class="field-group">
        <label><span class="icon-label">🔒 Password</span></label>
        <input type="password" name="password" placeholder="Min 6 characters">
      </div>
      <div class="field-group">
        <label><span class="icon-label">🔒 Confirm</span></label>
        <input type="password" name="confirm_password" placeholder="Re-enter password">
      </div>
    </div>

    <button type="submit" class="btn-submit">✨ Register Now</button>
  </form>

  <p class="login-link">Already have an account? <a href="login.php">Login here →</a></p>
</div>
</body>
</html>
