<?php
require __DIR__ . "/../../config/app.php";
session_unset();
session_destroy();
header('Location: /complaint-manager/views/auth/connexion.php');
exit;
