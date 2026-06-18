<?php
require_once __DIR__ . '/../config/config.php';
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true]);
