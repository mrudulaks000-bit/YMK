<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); exit;
}

/* ══════════════════════════════════════════════════════════════════════════
   REGISTERED USERS DATA
   ── In a real app this would come from a database.
      Here we use PHP session storage as a simple shared store.
      Users are saved when they submit login.php (see bottom of this file).
   ══════════════════════════════════════════════════════════════════════════ */

// Load stored users (persisted in a flat JSON file next to the scripts)
$dataFile = __DIR__ . '/registered_users.json';
$users    = [];
if (file_exists($dataFile)) {
    $users = json_decode(file_get_contents($dataFile), true) ?: [];
}

// ── Handle "Clear All" action ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'clear_all') {
    $users = [];
    file_put_contents($dataFile, json_encode($users));
    header('Location: admin_dashboard.php'); exit;
}

// ── Handle "Delete Single" action ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete' && isset($_POST['index'])) {
    $idx = (int)$_POST['index'];
    array_splice($users, $idx, 1);
    file_put_contents($dataFile, json_encode(array_values($users)));
    header('Location: admin_dashboard.php'); exit;
}

// ── Handle "Logout" ───────────────────────────────────────────────────────
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: admin_login.php'); exit;
}

$totalUsers  = count($users);
$eventCounts = array_count_values(array_column($users, 'event_date') ?: []);
$events      = array_column($users, 'selected_event');
$eventFreq   = array_count_values(array_filter($events));
arsort($eventFreq);
$topEvent    = !empty($eventFreq) ? ucfirst(array_key_first($eventFreq)) : '—';

// Search / filter
$search  = trim($_GET['q'] ?? '');
$filtered = $users;
if ($search !== '') {
    $filtered = array_filter($users, function($u) use ($search) {
        $s = strtolower($search);
        return str_contains(strtolower($u['name']   ?? ''), $s)
            || str_contains(strtolower($u['email']  ?? ''), $s)
            || str_contains(strtolower($u['phone']  ?? ''), $s)
            || str_contains(strtolower($u['address'] ?? ''), $s)
            || str_contains(strtolower($u['selected_event'] ?? ''), $s);
    });
}
$filtered = array_values($filtered);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>YMK – Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    /* ── Reset & Base ──────────────────────────────────────────────────── */
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      min-height:100vh;
      background:linear-gradient(135deg,#fff0f5 0%,#f0f4ff 100%);
      font-family:'Poppins',sans-serif;
      padding-bottom:60px;
    }

    /* ── Top Nav ─────────────────────────────────────────────────────── */
    .topnav{
      background:linear-gradient(135deg,#2d1b69,#5a3e8a);
      padding:0 32px;
      display:flex;align-items:center;justify-content:space-between;
      height:64px;
      box-shadow:0 4px 20px rgba(45,27,105,0.25);
      position:sticky;top:0;z-index:100;
    }
    .nav-brand{
      display:flex;align-items:center;gap:12px;
      font-family:'Playfair Display',serif;
      color:#fff;font-size:1.25rem;
    }
    .nav-brand span{font-size:1.6rem;}
    .nav-right{display:flex;align-items:center;gap:16px;}
    .nav-badge{
      background:rgba(255,255,255,0.18);
      color:#ffd700;border-radius:50px;
      padding:4px 14px;font-size:0.78rem;font-weight:600;letter-spacing:1px;
    }
    .btn-logout{
      background:linear-gradient(135deg,#ff6eb4,#a18cd1);
      color:#fff;border:none;border-radius:10px;
      padding:8px 18px;font-size:0.82rem;font-weight:600;
      cursor:pointer;font-family:'Poppins',sans-serif;
      text-decoration:none;letter-spacing:0.5px;
      transition:transform 0.2s,box-shadow 0.2s;
    }
    .btn-logout:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(255,110,180,0.4);}

    /* ── Page wrapper ────────────────────────────────────────────────── */
    .page{max-width:1200px;margin:0 auto;padding:36px 24px 0;}

    .page-title{
      font-family:'Playfair Display',serif;
      color:#2d1b69;font-size:2rem;margin-bottom:4px;
    }
    .page-sub{color:#a18cd1;font-size:0.9rem;letter-spacing:1px;margin-bottom:32px;}

    /* ── Stats row ───────────────────────────────────────────────────── */
    .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:32px;}
    .stat-card{
      background:#fff;border-radius:20px;
      box-shadow:0 4px 24px rgba(161,140,209,0.14);
      padding:24px 26px;
      display:flex;align-items:center;gap:18px;
      border-left:5px solid transparent;
      transition:transform 0.25s;
    }
    .stat-card:hover{transform:translateY(-4px);}
    .stat-card.purple{border-left-color:#a18cd1;}
    .stat-card.pink  {border-left-color:#ff6eb4;}
    .stat-card.gold  {border-left-color:#ffd700;}
    .stat-icon{font-size:2.2rem;}
    .stat-val{font-size:2rem;font-weight:700;color:#2d1b69;line-height:1;}
    .stat-lbl{font-size:0.78rem;color:#888;margin-top:3px;letter-spacing:1px;text-transform:uppercase;}

    /* ── Controls row ────────────────────────────────────────────────── */
    .controls{
      display:flex;flex-wrap:wrap;gap:14px;align-items:center;
      margin-bottom:22px;
    }
    .search-wrap{position:relative;flex:1;min-width:240px;}
    .search-wrap input{
      width:100%;padding:11px 16px 11px 40px;
      border:2px solid #e8d5f5;border-radius:12px;
      font-family:'Poppins',sans-serif;font-size:0.92rem;color:#333;
      outline:none;transition:border 0.3s;background:#fff;
    }
    .search-wrap input:focus{border-color:#a18cd1;}
    .search-wrap .s-icon{
      position:absolute;left:12px;top:50%;transform:translateY(-50%);
      font-size:1.1rem;pointer-events:none;
    }

    .btn-clear{
      background:linear-gradient(135deg,#ff6eb4,#a18cd1);
      color:#fff;border:none;border-radius:10px;
      padding:10px 20px;font-size:0.85rem;font-weight:600;
      cursor:pointer;font-family:'Poppins',sans-serif;
      transition:transform 0.2s,box-shadow 0.2s;
    }
    .btn-clear:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(255,110,180,0.35);}

    .result-count{font-size:0.82rem;color:#888;margin-left:4px;}

    /* ── Table card ──────────────────────────────────────────────────── */
    .table-card{
      background:#fff;border-radius:24px;
      box-shadow:0 6px 36px rgba(161,140,209,0.13);
      overflow:hidden;
    }
    .table-header{
      background:linear-gradient(135deg,#f8f0fb,#e8f4ff);
      padding:18px 26px;
      display:flex;align-items:center;justify-content:space-between;
      border-bottom:2px solid #f0e8fa;
    }
    .table-header h3{
      font-family:'Playfair Display',serif;
      color:#2d1b69;font-size:1.2rem;
    }

    .table-wrap{overflow-x:auto;}
    table{width:100%;border-collapse:collapse;}
    thead tr{background:linear-gradient(90deg,#f3eeff,#fdf0ff);}
    thead th{
      padding:14px 18px;text-align:left;
      font-size:0.75rem;font-weight:600;color:#5a3e8a;
      letter-spacing:1px;text-transform:uppercase;
      white-space:nowrap;
    }
    tbody tr{border-bottom:1px solid #f5f0fa;transition:background 0.2s;}
    tbody tr:hover{background:#fdf8ff;}
    tbody tr:last-child{border-bottom:none;}
    td{padding:14px 18px;font-size:0.88rem;color:#444;vertical-align:middle;}
    td .name-cell{font-weight:600;color:#2d1b69;}
    td .email-cell{color:#888;font-size:0.82rem;}

    .event-pill{
      display:inline-block;padding:3px 12px;border-radius:50px;
      font-size:0.74rem;font-weight:600;letter-spacing:0.5px;
    }
    .e-birthday   {background:#fff0f5;color:#ff6eb4;}
    .e-marriage   {background:#fff0f0;color:#e74c3c;}
    .e-engagement {background:#fff8e7;color:#f39c12;}
    .e-cocktail   {background:#f0fff4;color:#27ae60;}
    .e-babyshower {background:#fdf0ff;color:#9b59b6;}
    .e-naming     {background:#f0f4ff;color:#3498db;}
    .e-default    {background:#f3eeff;color:#5a3e8a;}

    .pkg-pill{
      display:inline-block;padding:3px 10px;border-radius:50px;
      font-size:0.72rem;font-weight:600;
    }
    .p-silver  {background:#eceff1;color:#546e7a;}
    .p-gold    {background:#fff9c4;color:#b8860b;}
    .p-platinum{background:#f3e5f5;color:#7b1fa2;}
    .p-default {background:#f3eeff;color:#5a3e8a;}

    .btn-del{
      background:none;border:2px solid #f5c6cb;color:#e74c3c;
      border-radius:8px;padding:5px 12px;font-size:0.78rem;
      cursor:pointer;font-family:'Poppins',sans-serif;font-weight:600;
      transition:background 0.2s,color 0.2s;
    }
    .btn-del:hover{background:#e74c3c;color:#fff;border-color:#e74c3c;}

    /* ── Empty state ─────────────────────────────────────────────────── */
    .empty-state{
      text-align:center;padding:60px 20px;
    }
    .empty-state .icon{font-size:3.5rem;margin-bottom:16px;display:block;}
    .empty-state p{color:#a18cd1;font-size:1rem;}

    /* ── Serial num ─────────────────────────────────────────────────── */
    .sno{color:#bbb;font-size:0.8rem;font-weight:600;}
  </style>
</head>
<body>

<!-- ── Navigation ───────────────────────────────────────────────────────── -->
<nav class="topnav">
  <div class="nav-brand">
    <span>🎊</span> YMK Event Planner
  </div>
  <div class="nav-right">
    <span class="nav-badge">🛡️ ADMIN</span>
    <a href="admin_dashboard.php?logout=1" class="btn-logout">🚪 Logout</a>
  </div>
</nav>

<!-- ── Page ─────────────────────────────────────────────────────────────── -->
<div class="page">

  <h1 class="page-title">📊 Admin Dashboard</h1>
  <p class="page-sub">VIEW &amp; MANAGE REGISTERED USERS</p>

  <!-- Stats -->
  <div class="stats">
    <div class="stat-card purple">
      <span class="stat-icon">👥</span>
      <div>
        <div class="stat-val"><?= $totalUsers ?></div>
        <div class="stat-lbl">Total Registrations</div>
      </div>
    </div>
    <div class="stat-card pink">
      <span class="stat-icon">🎉</span>
      <div>
        <div class="stat-val"><?= count($eventFreq) ?></div>
        <div class="stat-lbl">Event Types Booked</div>
      </div>
    </div>
    <div class="stat-card gold">
      <span class="stat-icon">🏆</span>
      <div>
        <div class="stat-val" style="font-size:1.2rem;padding-top:4px"><?= $topEvent ?></div>
        <div class="stat-lbl">Most Popular Event</div>
      </div>
    </div>
  </div>

  <!-- Controls -->
  <div class="controls">
    <form method="GET" action="admin_dashboard.php" style="display:contents;">
      <div class="search-wrap">
        <span class="s-icon">🔍</span>
        <input type="text" name="q" placeholder="Search by name, email, phone, event…"
               value="<?= htmlspecialchars($search) ?>">
      </div>
      <button type="submit" class="btn-clear">Search</button>
      <?php if ($search): ?>
        <a href="admin_dashboard.php" class="btn-clear" style="text-decoration:none;">✕ Clear</a>
      <?php endif; ?>
    </form>

    <?php if ($totalUsers > 0): ?>
      <form method="POST" action="admin_dashboard.php"
            onsubmit="return confirm('Delete ALL registered users? This cannot be undone.')">
        <input type="hidden" name="action" value="clear_all">
        <button type="submit" class="btn-clear" style="background:linear-gradient(135deg,#e74c3c,#c0392b);">
          🗑️ Clear All
        </button>
      </form>
    <?php endif; ?>

    <?php if ($search): ?>
      <span class="result-count"><?= count($filtered) ?> result(s) for "<strong><?= htmlspecialchars($search) ?></strong>"</span>
    <?php endif; ?>
  </div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <h3>👤 Registered Users</h3>
      <span style="font-size:0.82rem;color:#a18cd1;"><?= $totalUsers ?> total record<?= $totalUsers !== 1 ? 's' : '' ?></span>
    </div>

    <?php if (empty($filtered)): ?>
      <div class="empty-state">
        <span class="icon"><?= $search ? '🔎' : '📭' ?></span>
        <p><?= $search ? 'No users match your search.' : 'No users have registered yet.<br>They will appear here once they fill the registration form.' ?></p>
      </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>👤 Name</th>
            <th>🆔 Username</th>
            <th>📧 Email</th>
            <th>📱 Phone</th>
            <th>🏠 Address</th>
            <th>📅 Event Date</th>
            <th>🎉 Event Type</th>
            <th>🏅 Package</th>
            <th>💰 Price</th>
            <th>🗑</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filtered as $i => $u): ?>
            <?php
              $evRaw  = strtolower($u['selected_event'] ?? '');
              $evCls  = in_array($evRaw,['birthday','marriage','engagement','cocktail','babyshower','naming'])
                        ? 'e-'.$evRaw : 'e-default';
              $pkgRaw = strtolower($u['selected_package'] ?? '');
              $pkgCls = in_array($pkgRaw,['silver','gold','platinum']) ? 'p-'.$pkgRaw : 'p-default';
              // Map original index for delete
              $origIdx = $search ? array_search($u, $users) : $i;
            ?>
          <tr>
            <td class="sno"><?= $i + 1 ?></td>
            <td>
              <div class="name-cell"><?= htmlspecialchars($u['name'] ?? '—') ?></div>
            </td>
            <td style="font-weight:600;color:#5a3e8a;"><?= htmlspecialchars($u['username'] ?? '—') ?></td>
            <td class="email-cell"><?= htmlspecialchars($u['email'] ?? '—') ?></td>
            <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
            <td style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"
                title="<?= htmlspecialchars($u['address'] ?? '') ?>">
              <?= htmlspecialchars($u['address'] ?? '—') ?>
            </td>
            <td><?= htmlspecialchars($u['event_date'] ?? '—') ?></td>
            <td>
              <span class="event-pill <?= $evCls ?>">
                <?= htmlspecialchars(ucfirst($u['selected_event'] ?? '—')) ?>
              </span>
            </td>
            <td>
              <span class="pkg-pill <?= $pkgCls ?>">
                <?= htmlspecialchars(ucfirst($u['selected_package'] ?? '—')) ?>
              </span>
            </td>
            <td style="font-weight:700;color:#2d1b69;"><?= htmlspecialchars($u['price'] ?? '—') ?></td>
            <td>
              <form method="POST" action="admin_dashboard.php"
                    onsubmit="return confirm('Delete this user?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="index" value="<?= $origIdx ?>">
                <button type="submit" class="btn-del">✕</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div><!-- /.table-card -->

</div><!-- /.page -->
</body>
</html>
