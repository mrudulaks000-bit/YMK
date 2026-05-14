<?php
session_start();
if(empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$name = htmlspecialchars($_SESSION['user']['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK – Choose Event</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      background:linear-gradient(135deg,#fff0f5 0%,#f0f4ff 100%);
      font-family:'Poppins',sans-serif;
      padding:0 0 40px;
    }
    .topnav{
      background:linear-gradient(135deg,#2d1b69,#5a3e8a);
      padding:0 32px;
      display:flex;align-items:center;justify-content:space-between;
      height:60px;
      box-shadow:0 4px 20px rgba(45,27,105,0.22);
      margin-bottom:36px;
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
    .content{padding:0 20px;}
    h1{
      font-family:'Playfair Display',serif;
      text-align:center;color:#2d1b69;font-size:2rem;
      margin-bottom:6px;
    }
    .sub{text-align:center;color:#a18cd1;margin-bottom:40px;font-size:1rem;letter-spacing:1px;}
    .greeting{
      text-align:center;font-size:1.3rem;font-weight:600;color:#5a3e8a;
      margin-bottom:12px;
    }
    .grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
      gap:28px;max-width:1100px;margin:0 auto;
    }
    .event-card{
      background:#fff;border-radius:24px;
      box-shadow:0 6px 30px rgba(161,140,209,0.14);
      padding:32px 24px;text-align:center;
      transition:transform 0.28s,box-shadow 0.28s;
      cursor:pointer;text-decoration:none;
      border:2px solid transparent;
      animation:fadeUp 0.5s ease both;
    }
    .event-card:hover{
      transform:translateY(-8px);
      box-shadow:0 16px 40px rgba(161,140,209,0.28);
      border-color:#a18cd1;
    }
    .event-card:nth-child(1){animation-delay:0.1s;} 
    .event-card:nth-child(2){animation-delay:0.2s;}
    .event-card:nth-child(3){animation-delay:0.3s;}
    .event-card:nth-child(4){animation-delay:0.4s;}
    .event-card:nth-child(5){animation-delay:0.5s;}
    .event-card:nth-child(6){animation-delay:0.6s;}
    @keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
    .event-icon{font-size:3.5rem;margin-bottom:14px;display:block;}
    .event-name{
      font-family:'Playfair Display',serif;
      font-size:1.2rem;color:#2d1b69;margin-bottom:6px;
    }
    .event-desc{font-size:0.82rem;color:#888;}
    .badge{
      display:inline-block;margin-top:10px;padding:4px 14px;
      border-radius:50px;font-size:0.75rem;font-weight:600;
    }
  </style>
</head>
<body>
<nav class="topnav">
  <div class="nav-brand">🎊 YMK Event Planner</div>
  <div class="nav-user">
    <span class="nav-hello">👤 <?=$name?></span>
    <a href="logout.php" class="btn-logout">🚪 Logout</a>
  </div>
</nav>
<div class="content">
  <h1>🌸 YMK Event Planner</h1>
  <p class="greeting">Hello <?=$name?> 👋</p>
  <p class="sub">Choose Your Event Below</p>

  <div class="grid">

    <a class="event-card" href="packages.php?event=birthday">
      <span class="event-icon">🎂</span>
      <div class="event-name">Birthday</div>
      <div class="event-desc">Make their day unforgettable</div>
      <span class="badge" style="background:#fff0f5;color:#ff6eb4;">From ₹10,000</span>
    </a>

    <a class="event-card" href="packages.php?event=cocktail">
      <span class="event-icon">🍸</span>
      <div class="event-name">Cocktail Party</div>
      <div class="event-desc">Elegant evenings, perfect vibes</div>
      <span class="badge" style="background:#f0fff4;color:#27ae60;">From ₹15,000</span>
    </a>

    <a class="event-card" href="packages.php?event=engagement">
      <span class="event-icon">💍</span>
      <div class="event-name">Engagement</div>
      <div class="event-desc">Begin your forever together</div>
      <span class="badge" style="background:#fff8e7;color:#f39c12;">From ₹20,000</span>
    </a>

    <a class="event-card" href="packages.php?event=naming">
      <span class="event-icon">👶</span>
      <div class="event-name">Naming Ceremony</div>
      <div class="event-desc">Welcome the little one</div>
      <span class="badge" style="background:#f0f4ff;color:#3498db;">From ₹8,000</span>
    </a>

    <a class="event-card" href="packages.php?event=babyshower">
      <span class="event-icon">🍼</span>
      <div class="event-name">Baby Shower</div>
      <div class="event-desc">Celebrate the bundle of joy</div>
      <span class="badge" style="background:#fdf0ff;color:#9b59b6;">From ₹12,000</span>
    </a>

    <a class="event-card" href="packages.php?event=marriage">
      <span class="event-icon">💒</span>
      <div class="event-name">Marriage</div>
      <div class="event-desc">Your dream wedding awaits</div>
      <span class="badge" style="background:#fff0f0;color:#e74c3c;">From ₹50,000</span>
    </a>

  </div>
</div><!-- /.content -->
</body>
</html>