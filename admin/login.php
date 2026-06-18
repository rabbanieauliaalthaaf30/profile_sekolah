<?php
ob_start();
require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/admin/index.php');
}

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escape($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $user = fetch("SELECT * FROM users WHERE username = '$username' AND status = 'aktif'");

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama']     = $user['nama_lengkap'];
            $_SESSION['role']     = $user['role'];
            $_SESSION['foto']     = $user['foto_profil'];
            session_write_close();

            // Tidak langsung redirect — biarkan JS handle animasi dulu
            $success = true;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

$profil = fetch("SELECT * FROM profil_sekolah LIMIT 1");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?php echo clean($profil['nama_sekolah'] ?? 'Sekolah'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #1e40af 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ============ LOGIN CARD ============ */
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            animation: cardIn 0.5s ease both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }

        .login-logo { text-align: center; margin-bottom: 28px; }
        .login-logo i { font-size: 50px; color: #2563eb; margin-bottom: 12px; display: block; }
        .login-logo h4 { font-weight: 700; color: #1f2937; font-size: 18px; margin: 0 0 4px; }
        .login-logo p  { color: #6b7280; font-size: 13px; }

        .form-label { font-weight: 500; font-size: 14px; color: #374151; }
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 15px;
            transition: border-color 0.3s;
        }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .input-group-text {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #6b7280;
        }
        .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }
        .input-group:focus-within .input-group-text { border-color: #2563eb; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            margin-top: 10px;
            cursor: pointer;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }
        .btn-login:disabled { opacity: 0.8; cursor: not-allowed; transform: none; }

        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #6b7280; font-size: 14px; text-decoration: none; }
        .back-link a:hover { color: #2563eb; }

        .password-toggle {
            position: absolute; right: 15px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer; color: #9ca3af; z-index: 5;
            background: none; border: none;
        }
        .password-toggle:hover { color: #2563eb; }

        .bg-shapes { position: fixed; inset: 0; overflow: hidden; pointer-events: none; z-index: 0; }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            animation: bubbleFloat ease-in-out infinite alternate;
        }

        /* tiap gelembung punya ukuran, posisi, dan durasi berbeda */
        .b1  { width:320px; height:320px; top:-80px;  right:-60px;  animation-duration:7s;  animation-delay:0s;   }
        .b2  { width:200px; height:200px; bottom:-60px; left:-50px;  animation-duration:9s;  animation-delay:1s;   }
        .b3  { width:140px; height:140px; top:45%;    left:8%;       animation-duration:6s;  animation-delay:2s;   }
        .b4  { width:90px;  height:90px;  top:15%;    left:20%;      animation-duration:8s;  animation-delay:0.5s; }
        .b5  { width:60px;  height:60px;  bottom:20%; right:15%;     animation-duration:5s;  animation-delay:1.5s; }
        .b6  { width:110px; height:110px; bottom:10%; right:30%;     animation-duration:10s; animation-delay:3s;   }
        .b7  { width:70px;  height:70px;  top:30%;    right:10%;     animation-duration:7s;  animation-delay:2.5s; }
        .b8  { width:50px;  height:50px;  top:60%;    left:40%;      animation-duration:6s;  animation-delay:4s;   }
        .b9  { width:180px; height:180px; top:70%;    right:-40px;   animation-duration:11s; animation-delay:0.8s; }
        .b10 { width:80px;  height:80px;  top:5%;     left:45%;      animation-duration:8s;  animation-delay:3.5s; }

        @keyframes bubbleFloat {
            0%   { transform: translate(0px, 0px) scale(1); }
            25%  { transform: translate(15px, -20px) scale(1.04); }
            50%  { transform: translate(-10px, 25px) scale(0.97); }
            75%  { transform: translate(20px, 10px) scale(1.02); }
            100% { transform: translate(-15px, -15px) scale(1.05); }
        }

        /* ============ TRANSITION OVERLAY ============ */
        #transitionOverlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
        }

        /* Stage 1: ripple lingkaran dari tengah */
        .ripple-circle {
            position: absolute;
            top: 50%; left: 50%;
            width: 0; height: 0;
            border-radius: 50%;
            background: #2563eb;
            transform: translate(-50%, -50%);
        }

        /* Stage 2: checkmark card */
        .success-card {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            opacity: 0;
        }

        .check-circle {
            width: 90px; height: 90px;
            background: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            transform: scale(0);
        }

        .check-circle i {
            font-size: 40px;
            color: #2563eb;
            opacity: 0;
            transform: scale(0) rotate(-20deg);
        }

        .welcome-text {
            text-align: center;
            opacity: 0;
            transform: translateY(15px);
        }

        .welcome-text h3 {
            color: white;
            font-weight: 800;
            font-size: 24px;
            margin-bottom: 4px;
        }

        .welcome-text p {
            color: rgba(255,255,255,0.7);
            font-size: 15px;
        }

        /* Stage 3: swipe out ke kanan */
        .swipe-panel {
            position: absolute;
            inset: 0;
            background: #0f172a;
            transform: translateX(-100%);
        }

        /* ============ LOGIN CARD EXIT ============ */
        .login-wrapper.exit {
            animation: cardExit 0.4s ease forwards;
        }

        @keyframes cardExit {
            to { opacity: 0; transform: scale(0.9) translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="bubble b1"></div>
        <div class="bubble b2"></div>
        <div class="bubble b3"></div>
        <div class="bubble b4"></div>
        <div class="bubble b5"></div>
        <div class="bubble b6"></div>
        <div class="bubble b7"></div>
        <div class="bubble b8"></div>
        <div class="bubble b9"></div>
        <div class="bubble b10"></div>
    </div>

    <!-- Transition Overlay -->
    <div id="transitionOverlay">
        <div class="ripple-circle" id="rippleCircle"></div>
        <div class="success-card" id="successCard">
            <div class="check-circle" id="checkCircle">
                <i class="fas fa-check" id="checkIcon"></i>
            </div>
            <div class="welcome-text" id="welcomeText">
                <h3>Selamat Datang, <?php echo clean($user['nama_lengkap'] ?? $_SESSION['nama'] ?? 'Admin'); ?>!</h3>
                <p>Mengalihkan ke dashboard...</p>
            </div>
        </div>
        <div class="swipe-panel" id="swipePanel"></div>
    </div>

    <div class="login-wrapper" id="loginWrapper" style="position: relative; z-index: 1;">
        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-school"></i>
                <h4><?php echo clean($profil['nama_sekolah'] ?? 'SMA Negeri 1 Harapan Bangsa'); ?></h4>
                <p>Panel Admin &amp; Staff</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger py-2 mb-3" style="border-radius:10px; font-size:14px;">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo clean($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                               value="<?php echo isset($_POST['username']) ? clean($_POST['username']) : ''; ?>"
                               required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="passwordInput" class="form-control"
                                   placeholder="Masukkan password" required autocomplete="current-password">
                        </div>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>

            <div class="back-link">
                <a href="<?php echo SITE_URL; ?>"><i class="fas fa-arrow-left me-1"></i>Kembali ke Website</a>
            </div>
        </div>
    </div>

    <script>
    const DASHBOARD_URL = '<?php echo SITE_URL; ?>/admin/index.php';
    const LOGIN_SUCCESS = <?php echo $success ? 'true' : 'false'; ?>;

    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    // Loading state on submit (only when no success yet)
    document.getElementById('loginForm').addEventListener('submit', function() {
        if (!LOGIN_SUCCESS) {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memverifikasi...';
        }
    });

    // ── Main transition animation ──
    if (LOGIN_SUCCESS) {
        playLoginTransition();
    }

    function playLoginTransition() {
        const overlay      = document.getElementById('transitionOverlay');
        const ripple       = document.getElementById('rippleCircle');
        const successCard  = document.getElementById('successCard');
        const checkCircle  = document.getElementById('checkCircle');
        const checkIcon    = document.getElementById('checkIcon');
        const welcomeText  = document.getElementById('welcomeText');
        const swipePanel   = document.getElementById('swipePanel');
        const loginWrapper = document.getElementById('loginWrapper');

        // Show overlay
        overlay.style.display = 'block';

        // Step 1: card exits
        loginWrapper.classList.add('exit');

        setTimeout(() => {
            // Step 2: ripple expands from center
            overlay.style.background = 'transparent';
            ripple.style.transition   = 'width 0.6s cubic-bezier(0.4,0,0.2,1), height 0.6s cubic-bezier(0.4,0,0.2,1), opacity 0.6s';
            const size = Math.max(window.innerWidth, window.innerHeight) * 2.5;
            ripple.style.width   = size + 'px';
            ripple.style.height  = size + 'px';
        }, 250);

        setTimeout(() => {
            // Step 3: show success card
            successCard.style.transition = 'opacity 0.3s';
            successCard.style.opacity    = '1';
        }, 700);

        setTimeout(() => {
            // Step 4: check circle pops in
            checkCircle.style.transition = 'transform 0.4s cubic-bezier(0.34,1.56,0.64,1)';
            checkCircle.style.transform  = 'scale(1)';
        }, 800);

        setTimeout(() => {
            // Step 5: check icon spins in
            checkIcon.style.transition = 'opacity 0.3s, transform 0.4s cubic-bezier(0.34,1.56,0.64,1)';
            checkIcon.style.opacity    = '1';
            checkIcon.style.transform  = 'scale(1) rotate(0deg)';
        }, 950);

        setTimeout(() => {
            // Step 6: welcome text slides up
            welcomeText.style.transition = 'opacity 0.4s, transform 0.4s';
            welcomeText.style.opacity    = '1';
            welcomeText.style.transform  = 'translateY(0)';
        }, 1100);

        setTimeout(() => {
            // Step 7: dark panel swipes in from left
            swipePanel.style.transition = 'transform 0.55s cubic-bezier(0.77,0,0.175,1)';
            swipePanel.style.transform  = 'translateX(0)';
        }, 1900);

        setTimeout(() => {
            // Step 8: redirect
            window.location.href = DASHBOARD_URL;
        }, 2400);
    }
    </script>
</body>
</html>
