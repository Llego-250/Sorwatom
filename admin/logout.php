<?php
require_once __DIR__ . '/../config/app.php';
start_session(ADMIN_SESSION_NAME);
$_SESSION = [];
session_destroy();
header('Location: /admin/login.php');
exit;
