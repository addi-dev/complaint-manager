<?php
require __DIR__. "/../../config/app.php";
require __DIR__. "/../../core/CSRF.php";
CSRF::verify();
session_unset();
session_destroy();

header('Location: /complaint-manager/views/auth/login.php');
exit;