<?php 
    
    if (!isset($_SESSION['username'])){
        header('location:index.php');
        exit();
    }else{
        include 'connect.php';
        $connection = $conn->prepare("SELECT * FROM users WHERE group_id  = 1 AND user_id = ? ");
        $connection->execute([$_SESSION['user_id']]);
        if(!$connection->rowCount()){
            header('location:../index.php');
            exit();
        }
    }   
    
?>