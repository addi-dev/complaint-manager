<?php
session_start();
session_unset();
session_destroy();

header('Location: /complaint-manager/views/auth/login.php');
exit;