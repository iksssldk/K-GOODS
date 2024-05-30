<?php
if (!defined('DB_SERVER')) {
    define('DB_SERVER','localhost');
}

if (!defined('DB_USER')) {
    define('DB_USER','root');
}

if (!defined('DB_PASS')) {
    define('DB_PASS' ,'');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'shop');
}

$conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
// Check connection
if (mysqli_connect_errno()) {
    echo "Connection failed: " . mysqli_connect_error();
}
?>