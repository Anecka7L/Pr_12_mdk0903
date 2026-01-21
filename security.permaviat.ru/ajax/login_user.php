<?php
session_start();
include("../settings/connect_datebase.php");

$login = $_POST['login'];
$password = $_POST['password'];

// Ищем пользователя
$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");

$id = -1;
while($user_read = $query_user->fetch_row()) {
    $id = $user_read[0];
}

if($id != -1) {
    //  токен
    $token = md5(uniqid(rand(), true));
    $expires = time() + 3600; 
    
    //  в БД 
    $stmt = $mysqli->prepare("INSERT INTO auth_tokens (token, user_id, expires_at, ip) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $token, $id, $expires, $user_ip);
    
    if ($stmt->execute()) {
        //  cookies
        setcookie("auth_token", $token, [
            'expires' => $expires,
            'path' => '/',
            'secure' => false,
            'httponly' => true
        ]);
        
        setcookie("user_id", $id, [
            'expires' => $expires,
            'path' => '/'
        ]);
    } else {
        
        error_log("Ошибка при сохранении токена: " . $stmt->error);
    }
    
    $stmt->close();
}

echo md5(md5($id));
?>
