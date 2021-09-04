<?php
include 'admin/connect.php';


function updateIp($user_id , $ip){
    global $conn;
    $connection = $conn->prepare("UPDATE users SET user_ip = ? WHERE user_id = ?");
    $connection->execute([$ip , $user_id]);
    return $connection->rowCount();
}
