<?php
session_start();
include("../settings/connect_datebase.php");

// из бд
if (isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];
    $mysqli->query("DELETE FROM auth_tokens WHERE token = '$token'");
}

// cookies авторизации
if (isset($_COOKIE['auth_token'])) {
    setcookie("auth_token", "", time() - 3600, "/");
    unset($_COOKIE['auth_token']);
}

if (isset($_COOKIE['user_id'])) {
    setcookie("user_id", "", time() - 3600, "/");
    unset($_COOKIE['user_id']);
}

session_destroy();
?>