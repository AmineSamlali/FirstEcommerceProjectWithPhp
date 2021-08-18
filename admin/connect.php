<?php 
    
    $dsn = 'mysql:host=localhost;dbanme=shop';
    $user = 'root';
    $pass = '';
    $servername = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
    
    try{
        $conn = new PDO($dsn , $user , $pass ,$option);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $e){
        echo "Failed " . $e->getMessage();
        $err = $e->getMessage();
    }

