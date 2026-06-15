<?php
require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    logActivity($_SESSION['user_id'], 'LOGOUT', 'users', $_SESSION['user_id'], 'User logout');
}

session_destroy();
redirect(SITE_URL . '/admin/login.php');
