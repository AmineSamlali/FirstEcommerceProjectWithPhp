<?php 

    function checkField($table,$field,$condition=['filed_name' => '','value' => ''],$mode='available',$otheCon = ''){
        global $conn;
        if ($mode = 'available'){
            $condition_field_name = $condition['filed_name'];
            $condition_filed_value = $condition['value'];
            $sqlstatement = "SELECT $field FROM $table WHERE $condition_field_name = \"$condition_filed_value\" $otheCon";
            $sql = $conn->prepare($sqlstatement);
            $sql->execute();
            $data = $sql->rowCount();
            return $data;
            
        }
    };

    function sqlCount($cul,$table){
        global $conn;
        $connection = $conn->prepare("SELECT $cul FROM $table");
        $connection->execute();
        $data = $connection->rowCount();
        return $data;
    }

    function getLatest($fields , $table ,$by, $limit){
        global $conn;
        $connection = $conn->prepare("SELECT $fields FROM $table ORDER BY $by DESC LIMIT $limit");
        $connection->execute();
        $data = $connection->fetchAll();
        return $data;
    } 
    function fetchMyColumn($fields , $table , $condition , $returnMode = 'data' ,$fetchType = 'All'){
        global $conn;
        $connection = $conn->prepare("SELECT $fields FROM $table WHERE $condition");
        $connection->execute();
        if($fetchType === 'All'){
            $data = $connection->fetchAll();
        }elseif($fetchType === 'One'){
            $data = $connection->fetch();
        }
        return ($returnMode === 'json') ? json_decode($data) : $data;
    };
    function insertIntoTable($table,$fields , $filedsNumber , $value , $condition = ''){
        global $conn;
        $connection = $conn->prepare("INSER INTO $table($fields) VALUES" . str_repeat('?' , $filedsNumber). ")");
        $connection->execute($value);
        return $connection->rowCount();
    }
    function updateIp($user_id , $ip){
        global $conn;
        $connection = $conn->prepare("UPDATE users SET user_ip = ? WHERE user_id = ?");
        $connection->execute([$ip , $user_id]);
        return $connection->rowCount();
    }



    
    function addLog($log , $by , $mode = false , $who = ''){
        global $conn;
        
        $memory = $by;
        $toAction = ' ';

        if($mode === true){
            $sql = 'SELECT '. $memory['getField'] . ' FROM ' . $memory['table'] . ' WHERE '  . $memory['fieldName'] . ' = ' . $memory['value'];
            $connection = $conn->prepare($sql);
            $connection->execute();
            $data = $connection->fetchColumn();   
            $toAction = '"'.$data . '"';

            
        }else{
            $who = $by;
        }

        $connection2 = $conn->prepare("INSERT INTO log(log_text , log_datetime , log_by)VALUE(? ,? ,?) ");
        $connection2->execute([$log .$toAction . ' By "'.$who.'"' , date('Y-m-d H:i:s') ,$who]);
        $done = $connection2->rowCount();
    }




    function fetchAllFromTeble($field,$table,$condition = ''){
        global $conn;
        $connection = $conn->prepare("SELECT $field FROM $table $condition");
        $connection->execute();
        $data = $connection->fetchAll();
        return $data;
    }
    function checkUserStatus( $username = null , $password = null,$userId = null, $redirectMode=false ){
        global $conn;
        if($username and $password){
            $connection = $conn->prepare("SELECT trust_status FROM users WHERE username = ? AND password = ?");
            $connection->execute([$username,$password]);
            $data = $connection->fetch();
            if($connection->rowCount()){
                if(!$data['trust_status']){
                    if($redirectMode){
                        header('location:logout.php');
                        exit();
                    }else{
                        return $data;
                    }
                }    
            }
            return 1;
            
        }

    }

    function checkMaintenanceMode(){
        global $conn;
        $connection = $conn->prepare("SELECT maintenance_mode FROM settings WHERE id = 1");
        $connection->execute();
        $data = $connection->fetchColumn();
        if (!isset($_SESSION['group_id'])) {
            if ($data) {
                header('location:maintenance.php');
                exit();
            }    
        }else{
            if($_SESSION['group_id'] == 0){
                if ($data) {
                    header('location:maintenance.php');
                    exit();
                }        
            }
        }
        
    };