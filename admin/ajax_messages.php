<?php
    session_start();
    session_regenerate_id();

    include 'connect.php';

    include 'includes/functions/functions.php';
    include 'includes/functions/sql_functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['formType'] === 'getAllMessages') {
            if (is_numeric($_POST['mainMessage'])) {
                $check = checkField('shop.messages', '*', [
                    'filed_name'=>'message_id',
                    'value' => clean($_POST['mainMessage'])
                ]);
                if ($check) {
                    $connection = $conn->prepare("SELECT
                    shop.messages_sms.*,
                    shop.users.image
                        FROM
                        shop.messages_sms
                        INNER JOIN shop.users ON users.user_id = messages_sms.sms_from
                    WHERE message_main = ? ORDER BY sms_id ASC");
                    
                    $connection->execute([clean($_POST['mainMessage'])]);
                    $data = $connection->fetchAll();
                    echo json_encode($data);
                }
            }
        } elseif ($_POST['formType'] === 'getNewMessages') {
            if (isset($_POST['currentMsg']) and isset($_POST['lastSms'])) {
                if (is_numeric($_POST['currentMsg']) and is_numeric($_POST['lastSms'])) {
                    $currentMsg = $_POST['currentMsg'];
                    $lastSms = $_POST['lastSms'];
                    $checkForCurrentMsg =checkField('shop.messages', '*', ['filed_name' => 'message_id','value'=>$_POST['currentMsg']]);
                    $checkForSmsMsg = checkField('shop.messages_sms', '*', ['filed_name' => 'sms_id','value'=>$_POST['lastSms']]);
                    if ($checkForCurrentMsg and $checkForSmsMsg) {
                        $connection = $conn->prepare("SELECT
                        shop.messages_sms.*,
                        shop.users.image
                            FROM
                            shop.messages_sms
                            INNER JOIN shop.users ON users.user_id = messages_sms.sms_from
                        WHERE message_main = ? AND sms_id > ? ORDER BY sms_id ASC");
                        $connection->execute([$currentMsg,$lastSms]);
                        $data = $connection->fetchAll();
                        echo json_encode($data);
                    }
                }
            }
        } elseif ($_POST['formType'] === 'sendNewMessage') {
            if (isset($_POST['message']) and isset($_POST['currentMessage'])) {
                if (strlen($_POST['message']) > 0 and is_numeric($_POST['currentMessage'])) {
                    $check =checkField('shop.messages', '*', ['filed_name' => 'message_id','value'=>$_POST['currentMessage']]);
                    $connection = $conn->prepare("INSERT INTO shop.messages_sms(message_main ,sms_from ,sms_text) VALUES(?,?,?)");
                    $connection->execute([$_POST['currentMessage'] , $_SESSION['user_id'] , clean($_POST['message'])]);
                }
            }
        } elseif ($_POST['formType'] === 'removeSms') {
            if (isset($_POST['msgId']) and is_numeric($_POST['msgId'])) {
                $check =checkField('shop.messages_sms', '*', ['filed_name' => 'sms_id','value'=>$_POST['msgId']], 'available', ' AND sms_from = "'.$_SESSION['user_id']. '"');
                if ($check) {
                    echo "YES";
                    $connetion = $conn->prepare("DELETE FROM shop.messages_sms WHERE sms_id = ? ");
                    $connetion->execute([$_POST['msgId']]);
                    echo $connetion->rowCount();
                    
                    // beta
                    // $connection = $conn->prepare("UPDATE shop.messages_sms SET status = 0 WHERE ")
                }
            }
        } elseif ($_POST['formType'] === "checkIfDeleteMessages") {
            if (isset($_POST['currentMain']) and is_numeric($_POST['currentMain'])) {
                $checkForCurrentMsg =checkField('shop.messages', '*', ['filed_name' => 'message_id','value'=>$_POST['currentMain']]);
                if ($checkForCurrentMsg) {
                    $connection = $conn->prepare("SELECT
                    shop.messages_sms.*,
                    shop.users.image
                        FROM
                        shop.messages_sms
                        INNER JOIN shop.users ON users.user_id = messages_sms.sms_from
                    WHERE message_main = ? AND sms_status = 0 AND deleted = 0 AND sms_from != {$_SESSION['user_id']} ");
                    $connection->execute([$_POST['currentMain']]);
                    $data = $connection->fetchAll();
                    echo json_encode($data);
                }
            }
        } elseif ($_POST['formType'] === "checkMessages") {
            if (isset($_POST['currentMainMsg']) and is_numeric($_POST['currentMainMsg'])) {
                $checkForCurrentMsg =checkField('shop.messages', '*', ['filed_name' => 'message_id','value'=>$_POST['currentMainMsg']]);
                if ($checkForCurrentMsg) {
                    // $connection = $conn->prepare("")
                };
            };
        } elseif ($_POST['formType'] === "deleteConv") {
            if (isset($_POST['coverId']) and is_numeric($_POST['coverId'])) {
                $checkForCurrentMsg =checkField('shop.messages', '*', ['filed_name' => 'message_id','value'=>$_POST['coverId']]);
                if ($checkForCurrentMsg) {
                    $connection = $conn->prepare("DELETE FROM shop.messages WHERE message_id = ? ");
                    $connection->execute([$_POST['coverId']]);
                    echo $connection->rowCount();
                }
            }
        }
    }