<?php
require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn()) {
    redirect(SITE_URL . '/admin/login.php');
}

$nama = $_SESSION['nama'] ?? 'Admin';
$profil = fetch("SELECT nama_sekolah FROM profil_sekolah LIMIT 1");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout...</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ── Animated background ── */
        .bg-gradient {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0f172a 100%);
            background-size: 300% 300%;
            animation: bgMove 6s ease infinite;
        }

        @keyframes bgMove {
            0%, 100% { background-position: 0% 50%; }
            50%       { background-position: 100% 50%; }
        }

        /* ── Particles ── */
        .particles { position: fixed; inset: 0; pointer-events: none; }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            from { transform: translateY(110vh); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.4; }
            to   { transform: translateY(-10vh); opacity: 0; }
        }

        /* ── Main container ── */
        .logout-container {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
        }

        /* ── Avatar circle ── */
        .avatar-ring {
            position: relative;
            width: 110px;
            height: 110px;
            margin-bottom: 32px;
        }

        .avatar-ring svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .avatar-ring svg circle {
            fill: none;
            stroke-width: 3;
            stroke-linecap: round;
        }

        .avatar-ring .ring-bg {
            stroke: rgba(255,255,255,0.08);
        }

        .avatar-ring .ring-fill {
            stroke: #2563eb;
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
            animation: ringDraw 2s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
            filter: drop-shadow(0 0 6px rgba(37,99,235,0.7));
        }

        @keyframes ringDraw {
            to { stroke-dashoffset: 0; }
        }

        .avatar-inner {
            position: absolute;
            inset: 8px;
            background: linear-gradient(135deg, #1e40af, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 800;
            color: white;
            box-shadow:
                0 0 0 1px rgba(37,99,235,0.3),
                0 8px 24px rgba(37,99,235,0.4),
                inset 0 1px 0 rgba(255,255,255,0.15);
            animation: avatarIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        @keyframes avatarIn {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }

        /* ── Text ── */
        .goodbye-text {
            text-align: center;
            animation: textIn 0.5s ease 0.2s both;
        }

        @keyframes textIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .goodbye-text h2 {
            font-size: 26px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .goodbye-text p {
            color: rgba(255,255,255,0.45);
            font-size: 14px;
        }

        /* ── Progress bar ── */
        .progress-wrap {
            width: 220px;
            margin-top: 36px;
            animation: textIn 0.5s ease 0.4s both;
        }

        .progress-track {
            height: 3px;
            background: rgba(255,255,255,0.08);
            border-radius: 99px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            border-radius: 99px;
            box-shadow: 0 0 8px rgba(37,99,235,0.7);
            animation: progressGrow 2s cubic-bezier(0.4,0,0.2,1) 0.3s forwards;
        }

        @keyframes progressGrow {
            to { width: 100%; }
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .progress-label span {
            font-size: 12px;
            color: rgba(255,255,255,0.3);
        }

        .progress-label .dot-loader {
            display: flex;
            gap: 4px;
        }

        .dot-loader span {
            width: 5px;
            height: 5px;
            background: #2563eb;
            border-radius: 50%;
            animation: dotBounce 0.8s ease infinite;
        }
        .dot-loader span:nth-child(2) { animation-delay: 0.15s; }
        .dot-loader span:nth-child(3) { animation-delay: 0.3s; }

        @keyframes dotBounce {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40%            { transform: scale(1);   opacity: 1; }
        }

        /* ── Final swipe out ── */
        .swipe-out {
            position: fixed;
            inset: 0;
            background: #0f172a;
            transform: translateX(100%);
            z-index: 99;
        }

        .swipe-out.go {
            transition: transform 0.55s cubic-bezier(0.77,0,0.175,1);
            transform: translateX(0);
        }
    </style>
</head>
<body>

<div class="bg-gradient"></div>
<div class="particles" id="particles"></div>
<div class="swipe-out" id="swipeOut"></div>

<div class="logout-container">

    <!-- Avatar + ring progress -->
    <div class="avatar-ring">
        <svg viewBox="0 0 110 110">
            <circle class="ring-bg"   cx="55" cy="55" r="50"/>
            <circle class="ring-fill" cx="55" cy="55" r="50"/>
        </svg>
        <div class="avatar-inner">
            <?php echo mb_strtoupper(mb_substr($nama, 0, 1, 'UTF-8'), 'UTF-8'); ?>
        </div>
    </div>

    <!-- Teks -->
    <div class="goodbye-text">
        <h2>Sampai Jumpa, <?php echo clean(explode(' ', $nama)[0]); ?>!</h2>
        <p>Sesi Anda telah diakhiri dengan aman</p>
    </div>

    <!-- Progress bar -->
    <div class="progress-wrap">
        <div class="progress-track">
            <div class="progress-fill"></div>
        </div>
        <div class="progress-label">
            <span>Mengakhiri sesi...</span>
            <div class="dot-loader">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>

</div>

<script>
// ── Particles ──
(function() {
    const c = document.getElementById('particles');
    for (let i = 0; i < 20; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const s = Math.random() * 80 + 20;
        p.style.cssText = `width:${s}px;height:${s}px;left:${Math.random()*100}%;animation-duration:${Math.random()*15+8}s;animation-delay:${Math.random()*8}s;`;
        c.appendChild(p);
    }
})();

// ── Process logout then redirect ──
const LOGIN_URL = '<?php echo SITE_URL; ?>/admin/login.php';

// Hit logout-process.php to destroy session
fetch('<?php echo SITE_URL; ?>/admin/logout-process.php', { method: 'POST' })
    .catch(() => {}); // silent — session will be gone anyway

// After animation completes, swipe out then redirect
setTimeout(() => {
    document.getElementById('swipeOut').classList.add('go');
}, 2000);

setTimeout(() => {
    window.location.href = LOGIN_URL;
}, 2600);
</script>
</body>
</html>
