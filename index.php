<?php
// index.php - Cute PHP profile card with "Like" counter (session-based)

// Start session to store likes per visitor
session_start();
if (!isset($_SESSION['likes'])) {
    $_SESSION['likes'] = 0;
}

// Handle like button (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    // simple debounce: only increment once per session click
    if (empty($_SESSION['liked'])) {
        $_SESSION['likes'] += 1;
        $_SESSION['liked'] = true;
    } else {
        // allow toggling off (optional)
        $_SESSION['likes'] -= 1;
        $_SESSION['liked'] = false;
    }
    // Redirect to avoid form resubmission on refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Cute user data (could come from DB)
$user = [
    'name' => 'Luna',
    'role' => 'Pixel Artist & Coffee Dev',
    'bio'  => "J'aime le code propre, les chats en pixel art et les cappuccinos ☕️",
    'avatar' => null // leave null to use SVG avatar
];

$likes = $_SESSION['likes'];
$liked = !empty($_SESSION['liked']);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Carte mignonne — <?=htmlspecialchars($user['name'])?></title>
  <style>
    :root{
      --bg:#fff7fb;
      --card:#fff;
      --accent:#ff6fb5;
      --muted:#6b6b80;
      --shadow: 0 8px 24px rgba(170,90,140,0.12);
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    html,body{height:100%;margin:0;background:linear-gradient(135deg,#fffaef, #fff7fb);display:grid;place-items:center}
    .card{
      width:320px;
      background:var(--card);
      border-radius:18px;
      padding:20px;
      box-shadow:var(--shadow);
      text-align:center;
      transform:translateZ(0);
      position:relative;
      overflow:hidden;
    }
    .sparkle{position:absolute;right:-40px;top:-30px;opacity:0.15;font-size:120px;transform:rotate(25deg);pointer-events:none}
    .avatar{
      width:96px;height:96px;border-radius:50%;display:inline-grid;place-items:center;
      background:linear-gradient(135deg,#fff,#ffeef8);
      border:4px solid rgba(255,111,181,0.14);
      box-shadow:0 6px 18px rgba(0,0,0,0.06);
      margin-bottom:12px;
    }
    h1{margin:6px 0 0;font-size:18px;letter-spacing:0.2px}
    p.role{margin:6px 0 8px;color:var(--muted);font-size:13px}
    p.bio{font-size:13px;color:#4a4a5a;margin:8px 0 14px}
    .like-row{display:flex;gap:10px;align-items:center;justify-content:center}
    form button{
      background:linear-gradient(180deg,var(--accent),#ff4f9f);
      color:white;border:0;padding:10px 14px;border-radius:12px;font-weight:600;
      box-shadow:0 6px 14px rgba(255,111,181,0.18);cursor:pointer;
      display:inline-flex;gap:8px;align-items:center;transition:transform .12s ease;
    }
    form button:active{transform:translateY(1px) scale(.995)}
    .counter{
      background:rgba(0,0,0,0.03);padding:8px 12px;border-radius:999px;font-weight:700;
      min-width:56px;text-align:center;color:#333;
    }
    /* little heartbeat animation */
    .heart{display:inline-block;transition:transform .25s ease}
    .heart.loved{transform:scale(1.12) rotate(-8deg)}
    /* responsive */
    @media (max-width:360px){ .card{width:92vw;padding:16px} }
  </style>
</head>
<body>
  <div class="card" aria-live="polite">
    <div class="sparkle">✦</div>

    <div class="avatar" aria-hidden="true">
      <!-- simple SVG avatar -->
      <svg width="72" height="72" viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="avatar">
        <defs>
          <linearGradient id="g" x1="0" x2="1"><stop offset="0" stop-color="#fff"/><stop offset="1" stop-color="#ffeef8"/></linearGradient>
        </defs>
        <rect x="6" y="6" width="60" height="60" rx="12" fill="url(#g)"/>
        <g transform="translate(12,12)">
          <circle cx="24" cy="18" r="10" fill="#ffd5e8"/>
          <rect x="8" y="36" width="32" height="10" rx="5" fill="#ffd5e8"/>
          <circle cx="18" cy="16" r="2" fill="#5a2a3b"/>
          <circle cx="30" cy="16" r="2" fill="#5a2a3b"/>
          <path d="M14 22 Q24 28 34 22" stroke="#5a2a3b" stroke-width="1.5" fill="none" stroke-linecap="round"/>
        </g>
      </svg>
    </div>

    <h1><?=htmlspecialchars($user['name'])?> <span style="font-size:14px;color:var(--accent)">♥</span></h1>
    <p class="role"><?=htmlspecialchars($user['role'])?></p>
    <p class="bio"><?=htmlspecialchars($user['bio'])?></p>

    <div class="like-row">
      <form method="post" style="margin:0">
        <input type="hidden" name="like" value="1" />
        <button type="submit" aria-pressed="<?=($liked?'true':'false')?>">
          <span class="heart <?=($liked?'loved':'')?>" aria-hidden="true">💗</span>
          <span><?=($liked ? 'Unlike' : 'Like')?></span>
        </button>
      </form>

      <div class="counter" title="Total likes">
        <?= (int)$likes ?> ♥
      </div>
    </div>

    <p style="margin-top:14px;font-size:12px;color:#8a8a9a">Partage un sourire — code simple, résultat kawaii ✨</p>
  </div>
</body>
</html>
