<?php
session_start();
if(empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$user    = $_SESSION['user'];
$event   = htmlspecialchars($_GET['event']   ?? 'Birthday');
$package = htmlspecialchars($_GET['package'] ?? 'Gold');
$price   = htmlspecialchars($_GET['price']   ?? '₹25,000');

/* ── Update user's booking in the registered_users.json ───────────────── */
$dataFile = __DIR__ . '/registered_users.json';
$regKey   = 'booked_' . md5(($user['username'] ?? $user['email']) . $event . $package);
if (empty($_SESSION[$regKey])) {
    $users = [];
    if (file_exists($dataFile)) {
        $users = json_decode(file_get_contents($dataFile), true) ?: [];
    }
    // Find and update the existing user record by username or email
    $matched = false;
    foreach ($users as &$u) {
        $matchUser  = isset($user['username']) && strtolower($u['username'] ?? '') === strtolower($user['username']);
        $matchEmail = strtolower($u['email'] ?? '') === strtolower($user['email'] ?? '');
        if ($matchUser || $matchEmail) {
            $u['selected_event']   = $_GET['event']   ?? '';
            $u['selected_package'] = $_GET['package'] ?? '';
            $u['price']            = $_GET['price']   ?? '';
            $matched = true;
            break;
        }
    }
    unset($u);
    // If user not found (e.g. old session data), append a minimal record
    if (!$matched) {
        $users[] = [
            'name'             => $user['name']      ?? '',
            'username'         => $user['username']  ?? '',
            'email'            => $user['email']     ?? '',
            'phone'            => $user['phone']     ?? '',
            'address'          => $user['address']   ?? '',
            'event_date'       => $user['event_date'] ?? '',
            'selected_event'   => $_GET['event']     ?? '',
            'selected_package' => $_GET['package']   ?? '',
            'price'            => $_GET['price']     ?? '',
            'registered_at'    => date('Y-m-d H:i:s'),
        ];
    }
    file_put_contents($dataFile, json_encode(array_values($users), JSON_PRETTY_PRINT));
    $_SESSION[$regKey] = true;   // prevent duplicate on refresh
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK – Payment</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      background:linear-gradient(135deg,#e8f4ff 0%,#f8f0fb 100%);
      font-family:'Poppins',sans-serif;
      display:flex;flex-direction:column;align-items:stretch;
      padding:0;
    }
    .topnav{
      background:linear-gradient(135deg,#2d1b69,#5a3e8a);
      padding:0 32px;display:flex;align-items:center;
      justify-content:space-between;height:60px;
      box-shadow:0 4px 20px rgba(45,27,105,0.22);
      flex-shrink:0;
    }
    .nav-brand{font-family:'Playfair Display',serif;color:#fff;font-size:1.15rem;display:flex;align-items:center;gap:10px;}
    .nav-user{display:flex;align-items:center;gap:14px;}
    .nav-hello{color:#e8d5f5;font-size:0.85rem;font-weight:600;}
    .btn-logout{
      background:linear-gradient(135deg,#ff6eb4,#a18cd1);
      color:#fff;border:none;border-radius:10px;
      padding:7px 18px;font-size:0.82rem;font-weight:600;
      cursor:pointer;font-family:'Poppins',sans-serif;
      text-decoration:none;letter-spacing:0.5px;
      transition:transform 0.2s,box-shadow 0.2s;
    }
    .btn-logout:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(255,110,180,0.4);}
    .page-body{
      flex:1;display:flex;align-items:center;justify-content:center;
      padding:30px 20px;
    }
    .card{
      background:#fff;border-radius:28px;
      box-shadow:0 12px 50px rgba(161,140,209,0.18);
      padding:44px 50px;width:100%;max-width:500px;
      animation:slideUp 0.6s ease;
    }
    @keyframes slideUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
    h2{font-family:'Playfair Display',serif;color:#2d1b69;font-size:1.8rem;text-align:center;margin-bottom:6px;}
    .summary{
      background:#f8f0fb;border-radius:16px;
      padding:18px 22px;margin:20px 0;
    }
    .summary-row{display:flex;justify-content:space-between;padding:6px 0;font-size:0.92rem;}
    .summary-row span:first-child{color:#888;font-weight:600;}
    .summary-row span:last-child{color:#2d1b69;font-weight:700;}
    .total-row{border-top:2px solid #e0d0f0;margin-top:8px;padding-top:10px;font-size:1.05rem;}

    h3{font-size:1rem;font-weight:600;color:#5a3e8a;margin:22px 0 12px;letter-spacing:1px;}
    .btn-pay{
      width:100%;padding:14px;border:none;border-radius:14px;
      font-size:1rem;font-weight:600;cursor:pointer;
      font-family:'Poppins',sans-serif;margin-bottom:12px;
      transition:transform 0.2s,box-shadow 0.2s;letter-spacing:0.5px;
    }
    .btn-pay:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.14);}
    .btn-cash{background:linear-gradient(135deg,#43e97b,#38f9d7);color:#1a5a3a;}
    .btn-online{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;}

    /* Online methods panel */
    #online-panel{display:none;animation:fadeIn 0.4s ease;}
    @keyframes fadeIn{from{opacity:0}to{opacity:1}}
    .btn-phonepe{
      background:linear-gradient(135deg,#5f259f,#8B2FC9);color:#fff;
    }
    .btn-gpay{
      background:linear-gradient(135deg,#1a73e8,#34a853);color:#fff;
    }
    .upi-note{
      text-align:center;font-size:0.8rem;color:#aaa;margin-top:10px;
    }

    /* Success overlay */
    #success-overlay{
      display:none;position:fixed;inset:0;
      background:rgba(0,0,0,0.55);
      z-index:999;align-items:center;justify-content:center;
    }
    #success-overlay.show{display:flex;}
    .success-box{
      background:#fff;border-radius:28px;
      padding:50px 44px;text-align:center;
      box-shadow:0 24px 60px rgba(0,0,0,0.25);
      animation:popIn 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    @keyframes popIn{0%{transform:scale(0.5);opacity:0}80%{transform:scale(1.07)}100%{transform:scale(1);opacity:1}}
    .tick{font-size:5rem;margin-bottom:16px;}
    .success-box h2{color:#27ae60;font-family:'Playfair Display',serif;font-size:1.6rem;margin-bottom:10px;}
    .success-box p{color:#555;font-size:0.95rem;margin-bottom:6px;}
    .btn-home{
      display:inline-block;margin-top:22px;padding:12px 32px;
      background:linear-gradient(135deg,#ff6eb4,#a18cd1);
      color:#fff;border-radius:50px;text-decoration:none;
      font-weight:600;font-size:0.95rem;
    }

    .back-btn{
      display:inline-block;margin-bottom:20px;padding:7px 18px;
      background:#f8f0fb;border-radius:50px;
      color:#5a3e8a;text-decoration:none;font-size:0.85rem;font-weight:600;
    }
  </style>
</head>
<body>
<nav class="topnav">
  <div class="nav-brand">🎊 YMK Event Planner</div>
  <div class="nav-user">
    <span class="nav-hello">👤 <?=htmlspecialchars($user['name'])?></span>
    <a href="logout.php" class="btn-logout">🚪 Logout</a>
  </div>
</nav>

<!-- SUCCESS OVERLAY -->
<div id="success-overlay">
  <div class="success-box">
    <div class="tick">🎉</div>
    <h2>Order Submitted!</h2>
    <p>Thank you, <strong><?=htmlspecialchars($user['name'])?></strong>!</p>
    <p>Your <strong><?=$package?> <?=ucfirst($event)?></strong> package booking<br>has been successfully confirmed.</p>
    <p style="margin-top:10px;color:#a18cd1;font-size:0.85rem;">
      A confirmation will be sent to <strong><?=htmlspecialchars($user['email'])?></strong>
    </p>
    <a href="events.php" class="btn-home">🏠 Back to Home</a>
  </div>
</div>

<div class="page-body">
<div class="card">
  <a class="back-btn" href="javascript:history.back()">← Go Back</a>
  <h2>💳 Payment</h2>
  <p style="text-align:center;color:#a18cd1;font-size:0.85rem;margin-bottom:4px;">Complete your booking</p>

  <div class="summary">
    <div class="summary-row"><span>Customer</span><span><?=htmlspecialchars($user['name'])?></span></div>
    <div class="summary-row"><span>Event</span><span><?=ucfirst($event)?></span></div>
    <div class="summary-row"><span>Package</span><span><?=$package?></span></div>
    <div class="summary-row"><span>Event Date</span><span><?=htmlspecialchars($user['event_date'])?></span></div>
    <div class="summary-row total-row"><span>Total Amount</span><span><?=$price?></span></div>
  </div>

  <h3>SELECT PAYMENT METHOD</h3>

  <button class="btn-pay btn-cash" onclick="confirmPayment('Cash')">
    💵 Cash Payment
  </button>

  <button class="btn-pay btn-online" onclick="toggleOnline()">
    📲 Online Payment
  </button>

  <div id="online-panel">
    <h3>CHOOSE ONLINE OPTION</h3>
    <button class="btn-pay btn-phonepe" onclick="confirmPayment('PhonePe')">
      📲 PhonePe &nbsp;|&nbsp; UPI Pay
    </button>
    <button class="btn-pay btn-gpay" onclick="confirmPayment('Google Pay')">
      🟡 Google Pay &nbsp;|&nbsp; GPay
    </button>
    <p class="upi-note">🔒 Payments are secure & encrypted</p>
  </div>
</div>

<script>
  function toggleOnline(){
    const p = document.getElementById('online-panel');
    p.style.display = p.style.display === 'block' ? 'none' : 'block';
  }

  function confirmPayment(method){
    // Simulate a brief "processing" then show success
    const overlay = document.getElementById('success-overlay');
    setTimeout(()=>{
      overlay.classList.add('show');
    }, method === 'Cash' ? 300 : 1200);

    if(method !== 'Cash'){
      // Brief "processing" indicator
      document.querySelectorAll('.btn-pay').forEach(b=>b.disabled=true);
      document.querySelectorAll('.btn-pay').forEach(b=>b.style.opacity='0.6');
    }
  }
</script>
</div><!-- /.page-body -->
</body>
</html>