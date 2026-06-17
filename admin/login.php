<?php
ob_start();
require_once __DIR__ . '/../config/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect(SITE_URL . '/admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escape($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $user = fetch("SELECT * FROM users WHERE username = '$username' AND status = 'aktif'");

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID untuk keamanan
            session_regenerate_id(true);
            
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['nama']      = $user['nama_lengkap'];
            $_SESSION['role']      = $user['role'];
            $_SESSION['foto']      = $user['foto_profil'];

            // Log activity

            // Triple redirect: header + JS + meta refresh
            session_write_close();
            $redirect_url = SITE_URL . '/admin/index.php';
            ob_end_clean();
            header('Location: ' . $redirect_url);
            exit;
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #1e40af 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo img {
            height: 65px;
            margin-bottom: 12px;
        }
        .login-logo h4 {
            font-weight: 700;
            color: #1f2937;
            font-size: 18px;
            margin: 0;
        }
        .login-logo p {
            color: #6b7280;
            font-size: 13px;
            margin: 4px 0 0;
        }
        .form-label { font-weight: 500; font-size: 14px; color: #374151; }
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
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
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #6b7280; font-size: 14px; text-decoration: none; }
        .back-link a:hover { color: #2563eb; }
        .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #9ca3af; z-index: 5; background: none; border: none; }
        .password-toggle:hover { color: #2563eb; }
        .bg-shapes { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .shape { position: absolute; border-radius: 50%; opacity: 0.07; background: white; }
        .shape-1 { width: 300px; height: 300px; top: -100px; right: -80px; }
        .shape-2 { width: 200px; height: 200px; bottom: -50px; left: -60px; }
        .shape-3 { width: 150px; height: 150px; top: 50%; left: 10%; }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="login-wrapper" style="position: relative; z-index: 1;">
        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-school text-primary" style="font-size: 50px; margin-bottom: 12px;"></i>
                <h4><?php echo clean($profil['nama_sekolah'] ?? 'SMA Negeri 1 Harapan Bangsa'); ?></h4>
                <p>Panel Admin & Staff</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2 mb-3" style="border-radius: 10px; font-size: 14px;">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?php echo isset($_POST['username']) ? clean($_POST['username']) : ''; ?>" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>

            <div class="back-link">
                <a href="<?php echo SITE_URL; ?>"><i class="fas fa-arrow-left me-1"></i>Kembali ke Website</a>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>
</body>
</html>
