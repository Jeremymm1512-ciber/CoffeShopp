<?php
session_start();
session_unset();
session_destroy();
header("Location: /coffeeshop/index.php");
exit;
