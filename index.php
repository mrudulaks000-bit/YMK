<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK Event Planner</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      min-height:100vh;
      background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 25%, #ffd700 50%, #a18cd1 75%, #fbc2eb 100%);
      background-size: 400% 400%;
      animation: gradMove 6s ease infinite;
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      font-family:'Poppins',sans-serif;
      overflow:hidden;
    }
    @keyframes gradMove {
      0%{background-position:0% 50%}
      50%{background-position:100% 50%}
      100%{background-position:0% 50%}
    }

    /* floating circles */
    .bubble { position:absolute; border-radius:50%; opacity:0.18; animation:float 8s ease-in-out infinite; }
    .b1{width:200px;height:200px;background:#ff6eb4;top:5%;left:5%;animation-delay:0s;}
    .b2{width:130px;height:130px;background:#ffd700;top:60%;left:80%;animation-delay:1.5s;}
    .b3{width:90px;height:90px;background:#a18cd1;top:30%;left:75%;animation-delay:3s;}
    .b4{width:160px;height:160px;background:#43e97b;top:75%;left:10%;animation-delay:2s;}
    @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-30px)}}

    .card {
      background: rgba(255,255,255,0.28);
      backdrop-filter: blur(14px);
      border-radius: 32px;
      padding: 50px 60px;
      text-align:center;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
      border: 1.5px solid rgba(255,255,255,0.5);
      animation: fadeIn 1.2s ease;
      z-index:10; position:relative;
    }
    @keyframes fadeIn{from{opacity:0;transform:scale(0.9)}to{opacity:1;transform:scale(1)}}

    .logo-circle {
      width:160px; height:160px; border-radius:50%;
      background: linear-gradient(135deg,#ff6eb4,#ffd700,#a18cd1,#43e97b);
      margin:0 auto 24px;
      display:flex; align-items:center; justify-content:center;
      font-size:56px;
      box-shadow: 0 8px 32px rgba(255,110,180,0.4);
      animation: spin 15s linear infinite;
    }
    @keyframes spin{from{filter:hue-rotate(0deg)}to{filter:hue-rotate(360deg)}}

    h1 {
      font-family:'Playfair Display',serif;
      font-size:2.4rem; color:#2d1b69;
      text-shadow: 2px 2px 0 rgba(255,255,255,0.6);
      margin-bottom:10px;
    }
    .tagline {
      font-size:1.05rem; color:#5a3e8a; letter-spacing:3px;
      font-weight:600; margin-bottom:20px;
    }
    .words {
      display:flex; gap:18px; justify-content:center; flex-wrap:wrap;
      margin-top:16px;
    }
    .word-chip {
      padding:8px 22px; border-radius:50px;
      font-weight:600; font-size:0.9rem; letter-spacing:1px;
      animation: popIn 0.6s ease both;
    }
    .word-chip:nth-child(1){background:#ff6eb4;color:#fff;animation-delay:0.3s;}
    .word-chip:nth-child(2){background:#ffd700;color:#5a3e00;animation-delay:0.6s;}
    .word-chip:nth-child(3){background:#a18cd1;color:#fff;animation-delay:0.9s;}
    @keyframes popIn{from{opacity:0;transform:scale(0.5)}to{opacity:1;transform:scale(1)}}

    .loader-bar {
      width:300px; height:6px; background:rgba(255,255,255,0.3);
      border-radius:10px; margin:28px auto 0; overflow:hidden;
    }
    .loader-fill {
      height:100%; background:linear-gradient(90deg,#ff6eb4,#ffd700,#a18cd1);
      border-radius:10px;
      animation: load 10s linear forwards;
    }
    @keyframes load{from{width:0%}to{width:100%}}
  </style>
</head>
<body>
  <div class="bubble b1"></div>
  <div class="bubble b2"></div>
  <div class="bubble b3"></div>
  <div class="bubble b4"></div>

  <div class="card">
    <div class="logo-circle">🎉</div>
    <p class="tagline">✨ WELCOME TO ✨</p>
    <h1>YMK Event Planner</h1>
    <p style="color:#5a3e8a;margin-top:8px;font-size:0.95rem;">Crafting moments that last a lifetime</p>
    <div class="words">
      <span class="word-chip">📋 Plan</span>
      <span class="word-chip">🗓️ Organize</span>
      <span class="word-chip">🥂 Celebrate</span>
    </div>
    <div class="loader-bar"><div class="loader-fill"></div></div>
    <p style="margin-top:10px;font-size:0.8rem;color:#7a5ea7;">Loading your experience...</p>
  </div>

  <script>setTimeout(()=>{ window.location='login.php'; }, 5000);</script>
  <a href="admin_login.php" style="
    position:fixed;bottom:20px;right:24px;
    background:rgba(45,27,105,0.85);color:#ffd700;
    text-decoration:none;border-radius:50px;
    padding:8px 20px;font-size:0.8rem;font-weight:600;
    letter-spacing:1px;backdrop-filter:blur(8px);
    box-shadow:0 4px 16px rgba(45,27,105,0.3);
    transition:background 0.2s;
  ">🛡️ ADMIN</a>
</body>
</html>