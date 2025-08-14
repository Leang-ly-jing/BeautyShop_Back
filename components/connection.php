<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "demo";
$db_port = 3307;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name, $db_port);
} catch (mysqli_sql_exception $e) {
    die("Error on " . $e->getMessage());
}

function unique_id($length = 20) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $randomString;
}
?>
