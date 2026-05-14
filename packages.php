<?php
session_start();
if(empty($_SESSION['user'])) { header('Location: login.php'); exit; }

$event = $_GET['event'] ?? 'birthday';
$_SESSION['selected_event'] = $event;

// ── All packages data ──────────────────────────────────────────────────────
$data = [

  'birthday' => [
    'title'  => '🎂 Birthday Packages',
    'color'  => '#ff6eb4',
    'bg'     => '#fff0f5',
    'deco_emoji' => '🎈',
    'packages' => [
      'Silver' => [
        'price'    => '₹10,000',
        'color'    => '#b0bec5',
        'gradient' => 'linear-gradient(135deg,#b0bec5,#eceff1)',
        'items'    => ['🎈 50 Balloon Decorations','🎂 1-Tier Custom Cake (1 kg)','📸 Basic Photography (1 hr)','🪑 Seating for 30 Guests','🎀 Table Decoration','🕯️ Candles & Confetti'],
        'deco'     => '🎈 Pastel Balloon Arch'
      ],
      'Gold' => [
        'price'    => '₹25,000',
        'color'    => '#f9a825',
        'gradient' => 'linear-gradient(135deg,#f9a825,#fff9c4)',
        'items'    => ['🎈 100 Premium Balloons','🎂 2-Tier Fondant Cake (2 kg)','📸 Photography + Video (3 hrs)','🪑 Seating for 60 Guests','🌸 Floral Table Centrepieces','🎤 DJ Music (2 hrs)','🍽️ Snacks & Beverages','🎁 Return Gifts (20 pcs)'],
        'deco'     => '🌸 Floral + Balloon Grand Setup'
      ],
      'Platinum' => [
        'price'    => '₹50,000',
        'color'    => '#7b1fa2',
        'gradient' => 'linear-gradient(135deg,#7b1fa2,#e1bee7)',
        'items'    => ['🎈 200 Luxury Balloons + Arch','🎂 3-Tier Designer Cake (4 kg)','📸 Full-Day Photography + Drone','🪑 Seating for 150 Guests','🌺 Full Venue Floral Decoration','🎤 DJ + Live Music (5 hrs)','🍽️ Full Catering (Veg/Non-Veg)','🎁 Personalised Return Gifts','🚗 Decorated Entry Car','💡 LED Lighting Setup'],
        'deco'     => '👑 Grand Royal Balloon & Floral Setup'
      ],
    ]
  ],

  'cocktail' => [
    'title'  => '🍸 Cocktail Party Packages',
    'color'  => '#27ae60',
    'bg'     => '#f0fff4',
    'deco_emoji' => '🥂',
    'packages' => [
      'Silver' => [
        'price'    => '₹15,000',
        'color'    => '#b0bec5',
        'gradient' => 'linear-gradient(135deg,#b0bec5,#eceff1)',
        'items'    => ['🥂 Basic Bar Setup (Mocktails)','🍕 Snacks for 30 Guests','🎵 Background Music Playlist','💡 Ambient Lighting','🪑 Seating for 30 Guests','🎀 Simple Venue Decoration'],
        'deco'     => '✨ Minimalist Ambient Setup'
      ],
      'Gold' => [
        'price'    => '₹30,000',
        'color'    => '#f9a825',
        'gradient' => 'linear-gradient(135deg,#f9a825,#fff9c4)',
        'items'    => ['🍸 Premium Bar (Cocktails + Mocktails)','🍽️ Finger Food Buffet (50 pax)','🎤 DJ for 3 hrs','💡 LED + Neon Lights','📸 Photography (2 hrs)','🌿 Greenery Decor + Drapes','🎁 Welcome Hampers (20 pcs)'],
        'deco'     => '🌿 Neon Lights + Tropical Decor'
      ],
      'Platinum' => [
        'price'    => '₹60,000',
        'color'    => '#7b1fa2',
        'gradient' => 'linear-gradient(135deg,#7b1fa2,#e1bee7)',
        'items'    => ['🍸 Luxury Full Bar Setup','🍽️ 5-Course Gourmet Dinner (100 pax)','🎤 DJ + Live Band (5 hrs)','💡 Full Venue Lighting Design','📸 Photo + Video + Drone','🌺 Luxury Floral Decor','🎁 Customised Welcome Hampers','🚀 Red Carpet Entry','🪞 Mirror Photo Booth'],
        'deco'     => '🔴 Red Carpet + Luxury Neon Setup'
      ],
    ]
  ],

  'engagement' => [
    'title'  => '💍 Engagement Packages',
    'color'  => '#f39c12',
    'bg'     => '#fff8e7',
    'deco_emoji' => '💐',
    'packages' => [
      'Silver' => [
        'price'    => '₹20,000',
        'color'    => '#b0bec5',
        'gradient' => 'linear-gradient(135deg,#b0bec5,#eceff1)',
        'items'    => ['💐 Basic Floral Stage Decor','💍 Ring Ceremony Setup','🎂 Engagement Cake (1 kg)','📸 Photography (1 hr)','🪑 Seating for 40 Guests','🕯️ Candle Pathway'],
        'deco'     => '🌸 Simple Floral Stage'
      ],
      'Gold' => [
        'price'    => '₹40,000',
        'color'    => '#f9a825',
        'gradient' => 'linear-gradient(135deg,#f9a825,#fff9c4)',
        'items'    => ['💐 Premium Rose Petal Stage','💍 Ring Ceremony + Couple Throne','🎂 Designer Cake (2 kg)','📸 Photography + Cinematic Video','🪑 Seating for 80 Guests','🎶 Live Music (2 hrs)','🍽️ Dinner Buffet (60 pax)','🎁 Couple Gift Hamper'],
        'deco'     => '🌹 Rose Petal + Fairy Light Canopy'
      ],
      'Platinum' => [
        'price'    => '₹80,000',
        'color'    => '#7b1fa2',
        'gradient' => 'linear-gradient(135deg,#7b1fa2,#e1bee7)',
        'items'    => ['💐 Grand Floral Stage + Backdrop','💍 Premium Couple Throne','🎂 3-Tier Designer Cake','📸 Full Day Photo + Drone Video','🪑 Seating for 150 Guests','🎤 DJ + Live Orchestra','🍽️ Full Catering (Veg/Non-Veg)','🎁 Luxury Couple Hampers','🕯️ Candle Float Pool Decor','💡 LED Uplighting Full Venue'],
        'deco'     => '👑 Grand Palace-Style Floral Setup'
      ],
    ]
  ],

  'marriage' => [
    'title'  => '💒 Marriage Packages',
    'color'  => '#e74c3c',
    'bg'     => '#fff5f5',
    'deco_emoji' => '🌸',
    'packages' => [
      'Silver' => [
        'price'    => '₹50,000',
        'color'    => '#b0bec5',
        'gradient' => 'linear-gradient(135deg,#b0bec5,#eceff1)',
        'items'    => ['🌸 Mandap Decoration (Basic)','🎂 Wedding Cake (2 kg)','📸 Photography (4 hrs)','🪑 Seating for 100 Guests','🌺 Gate Flower Decoration','🍽️ Lunch Buffet (80 pax)','🎵 Background Music'],
        'deco'     => '🌸 Traditional Floral Mandap'
      ],
      'Gold' => [
        'price'    => '₹1,20,000',
        'color'    => '#f9a825',
        'gradient' => 'linear-gradient(135deg,#f9a825,#fff9c4)',
        'items'    => ['🌸 Premium Mandap + Stage','💐 Floral Car Decoration','🎂 Designer Wedding Cake (4 kg)','📸 Full Day Photo + Video','🪑 Seating for 200 Guests','🌺 Full Venue Floral Decor','🍽️ Lunch + Dinner Buffet','🎤 DJ + Live Shehnai','💡 LED Lighting','🎁 Wedding Favours (100 pcs)'],
        'deco'     => '🌺 Grand Floral + LED Venue Setup'
      ],
      'Platinum' => [
        'price'    => '₹2,50,000',
        'color'    => '#7b1fa2',
        'gradient' => 'linear-gradient(135deg,#7b1fa2,#e1bee7)',
        'items'    => ['👑 Royal Mandap + Jai Mala Stage','🚗 Decorated Bridal Car + Procession','🎂 5-Tier Designer Cake','📸 Full Day Photo + Drone + Cinematic','🪑 Seating for 500 Guests','🌺 Luxury Full Venue Floral','🍽️ Full Catering (All Meals + Live Stations)','🎤 DJ + Orchestra + Live Band','💡 Full Lighting + Projection Mapping','🎁 Premium Wedding Favours','💐 Bridal Suite Decoration','🎇 Fireworks & Sparklers'],
        'deco'     => '👑 Royal Palace Grand Wedding Setup'
      ],
    ]
  ],

  'babyshower' => [
    'title'  => '🍼 Baby Shower Packages',
    'color'  => '#9b59b6',
    'bg'     => '#fdf0ff',
    'deco_emoji' => '🌈',
    'packages' => [
      'Silver' => [
        'price'    => '₹12,000',
        'color'    => '#b0bec5',
        'gradient' => 'linear-gradient(135deg,#b0bec5,#eceff1)',
        'items'    => ['🌈 Pastel Balloon Decoration','🎂 Baby Shower Cake (1 kg)','📸 Photography (1 hr)','🪑 Seating for 30 Guests','🍬 Candy Corner Setup','🎀 Baby-themed Table Decor'],
        'deco'     => '🌈 Pastel Rainbow Balloon Setup'
      ],
      'Gold' => [
        'price'    => '₹28,000',
        'color'    => '#f9a825',
        'gradient' => 'linear-gradient(135deg,#f9a825,#fff9c4)',
        'items'    => ['🌈 Premium Balloon + Flower Decor','🎂 Designer Cake (2 kg)','📸 Photography + Video (3 hrs)','🪑 Seating for 70 Guests','🎮 Baby Shower Games Setup','🍽️ Snacks + High Tea Buffet','🎁 Baby Hampers (20 pcs)','🍬 Dessert Table'],
        'deco'     => '🌸 Cloud & Floral Dream Setup'
      ],
      'Platinum' => [
        'price'    => '₹55,000',
        'color'    => '#7b1fa2',
        'gradient' => 'linear-gradient(135deg,#7b1fa2,#e1bee7)',
        'items'    => ['🌈 Luxury Balloon Arch + Floral Wall','🎂 3-Tier Theme Cake (4 kg)','📸 Full Photography + Drone','🪑 Seating for 120 Guests','🎮 Full Games + Activity Zone','🍽️ Full Catering (Lunch + Desserts)','🎁 Premium Baby Hampers (50 pcs)','🎀 Mum-to-Be Sash & Crown','📸 Photo Booth with Props','💡 Fairy Light Venue Decor'],
        'deco'     => '✨ Enchanted Garden Baby Setup'
      ],
    ]
  ],

  'naming' => [
    'title'  => '👶 Naming Ceremony Packages',
    'color'  => '#3498db',
    'bg'     => '#f0f4ff',
    'deco_emoji' => '🌟',
    'packages' => [
      'Silver' => [
        'price'    => '₹8,000',
        'color'    => '#b0bec5',
        'gradient' => 'linear-gradient(135deg,#b0bec5,#eceff1)',
        'items'    => ['🌟 Simple Floral Decor','🎂 Naming Ceremony Cake (1 kg)','📸 Photography (1 hr)','🪑 Seating for 25 Guests','🌸 Baby Cradle Decoration','🎀 Welcome Gate Decor'],
        'deco'     => '🌸 Simple Traditional Floral Setup'
      ],
      'Gold' => [
        'price'    => '₹20,000',
        'color'    => '#f9a825',
        'gradient' => 'linear-gradient(135deg,#f9a825,#fff9c4)',
        'items'    => ['🌟 Premium Floral + Balloon Decor','🎂 Designer Cake (2 kg)','📸 Photography + Video (2 hrs)','🪑 Seating for 60 Guests','🌸 Decorated Baby Cradle + Stage','🍽️ Lunch Buffet (50 pax)','🎁 Return Gifts (20 pcs)','💡 String Light Venue'],
        'deco'     => '🌟 Golden Cradle & Floral Stage'
      ],
      'Platinum' => [
        'price'    => '₹40,000',
        'color'    => '#7b1fa2',
        'gradient' => 'linear-gradient(135deg,#7b1fa2,#e1bee7)',
        'items'    => ['🌟 Grand Floral Full Venue Decor','🎂 3-Tier Theme Cake','📸 Full Photography + Cinematic Video','🪑 Seating for 120 Guests','🌸 Royal Decorated Cradle + Thematic Stage','🍽️ Full Catering (All Meals)','🎁 Premium Return Gifts (60 pcs)','💡 Full LED Lighting','🎶 Live Classical Music','📿 Personalised Name Boards & Signage'],
        'deco'     => '👑 Royal Heritage Naming Setup'
      ],
    ]
  ],
];

$e      = $data[$event] ?? $data['birthday'];
$title  = $e['title'];
$color  = $e['color'];
$bg     = $e['bg'];
$pkgs   = $e['packages'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>YMK – <?=htmlspecialchars($title)?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      background:<?=$bg?>;
      font-family:'Poppins',sans-serif;
      padding:0 0 40px;
    }
    .topnav{
      background:linear-gradient(135deg,#2d1b69,#5a3e8a);
      padding:0 32px;display:flex;align-items:center;
      justify-content:space-between;height:60px;
      box-shadow:0 4px 20px rgba(45,27,105,0.22);
      margin-bottom:0;
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
    .content{padding:30px 20px 0;}
    .back-btn{
      display:inline-block;padding:8px 20px;
      background:rgba(255,255,255,0.7);border-radius:50px;
      color:#5a3e8a;text-decoration:none;font-size:0.85rem;font-weight:600;
      margin-bottom:24px;border:1.5px solid <?=$color?>;
      transition:background 0.2s;
    }
    .back-btn:hover{background:#fff;}
    h1{
      font-family:'Playfair Display',serif;
      text-align:center;color:#2d1b69;font-size:2rem;
      margin-bottom:36px;
    }
    .grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
      gap:32px;max-width:1100px;margin:0 auto;
    }
    .pkg-card{
      background:#fff;border-radius:28px;
      box-shadow:0 8px 36px rgba(0,0,0,0.10);
      overflow:hidden;
      animation:fadeUp 0.5s ease both;
    }
    .pkg-card:nth-child(1){animation-delay:0.1s;}
    .pkg-card:nth-child(2){animation-delay:0.2s;}
    .pkg-card:nth-child(3){animation-delay:0.3s;}
    @keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
    .pkg-header{
      padding:28px 24px 20px;
      text-align:center;
    }
    .pkg-name{
      font-family:'Playfair Display',serif;
      font-size:1.7rem;color:#fff;
      text-shadow:1px 2px 8px rgba(0,0,0,0.18);
      margin-bottom:6px;
    }
    .pkg-price{
      font-size:1.5rem;font-weight:700;color:#fff;
      background:rgba(0,0,0,0.15);
      display:inline-block;padding:4px 20px;
      border-radius:50px;margin-top:4px;
    }
    .deco-box{
      margin:0 20px;border-radius:16px;
      background:linear-gradient(135deg,#f8f8f8,#fffbe7);
      padding:18px;text-align:center;
      font-size:2.5rem;border:1.5px dashed #ddd;
    }
    .deco-label{font-size:0.8rem;color:#888;margin-top:6px;font-weight:600;}
    .items-list{padding:20px 28px;}
    .items-list li{
      list-style:none;padding:7px 0;
      font-size:0.9rem;color:#444;
      border-bottom:1px dashed #f0e6ff;
    }
    .items-list li:last-child{border:none;}
    .btn-choose{
      display:block;margin:16px 20px 24px;padding:13px;
      border:none;border-radius:14px;cursor:pointer;
      font-size:1rem;font-weight:600;color:#fff;
      font-family:'Poppins',sans-serif;
      text-decoration:none;text-align:center;
      transition:transform 0.2s,box-shadow 0.2s;
    }
    .btn-choose:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,0.2);}
  </style>
</head>
<body>
<nav class="topnav">
  <div class="nav-brand">🎊 YMK Event Planner</div>
  <div class="nav-user">
    <span class="nav-hello">👤 <?=htmlspecialchars($_SESSION['user']['name'])?></span>
    <a href="logout.php" class="btn-logout">🚪 Logout</a>
  </div>
</nav>
<div class="content">
  <a class="back-btn" href="events.php">← Back to Events</a>
  <h1><?=$title?></h1>

  <div class="grid">
  <?php foreach($pkgs as $pkgName => $pkg): ?>
    <div class="pkg-card">
      <div class="pkg-header" style="background:<?=$pkg['gradient']?>">
        <div class="pkg-name"><?=$pkgName?> Package</div>
        <div class="pkg-price"><?=$pkg['price']?></div>
      </div>
      <div class="deco-box">
        <div style="font-size:3rem;"><?=$e['deco_emoji']?></div>
        <div class="deco-label"><?=htmlspecialchars($pkg['deco'])?></div>
      </div>
      <ul class="items-list">
        <?php foreach($pkg['items'] as $item): ?>
          <li><?=htmlspecialchars($item)?></li>
        <?php endforeach; ?>
      </ul>
      <a class="btn-choose"
         href="payment.php?event=<?=urlencode($event)?>&package=<?=urlencode($pkgName)?>&price=<?=urlencode($pkg['price'])?>"
         style="background:<?=$pkg['gradient']?>">
        Choose <?=$pkgName?> – <?=$pkg['price']?>
      </a>
    </div>
  <?php endforeach; ?>
  </div>
</div><!-- /.content -->
</body>
</html>