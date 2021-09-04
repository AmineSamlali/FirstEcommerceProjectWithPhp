

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title><?php echo getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css ?>front.css" />
    
</head>

<body>
    <?php if (isset($_SESSION['username'])) {
        // echo checkUserStatus($_SESSION['username'],$_SESSION['password']);?>



    <div class="upper-bar">
        <div class="container">
        <div style="float:right;" class="btn-group my-info">
            <span id="notifDiv" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span id="notifStatus"></span> Notifications
                    <span class="caret">
                    </span>
                </span>
                <!-- <ul class="dropdown-menu" id="allNotifications">

                < ?php
                        if(!isset($noTrack)){
                            $connection = $conn->prepare("SELECT
                            messages.message_id,
                            messages_sms.sms_from,
                            messages_sms.sms_id,
                            users.image,
                            users.username,
                            products.Added_by,
                            products.Name
                        FROM
                            messages
                        INNER JOIN messages_sms ON
                            messages.message_id = messages_sms.message_main
                        INNER JOIN users ON
                            users.user_id = messages_sms.sms_from
                        INNER JOIN products ON
                            products.product_id = messages.added_to
                        WHERE
                            messages_sms.sms_from != ?
                            AND products.Added_by = ?
                        ORDER BY
                            messages_sms.sms_id
                        DESC
                        LIMIT 10
                            
                            
                        ");
                        outPutArray($_SESSION);

                            $connection->execute([$_SESSION['user_id'],$_SESSION['user_id']]);
                            foreach($connection->fetchAll() AS $notification){
                                echo '<li style="color:white;background-color:black;"><a notificationid="'.$notification['sms_id'].'" href="mymessages.php"><img class="my-image img-thumbnail img-circle" src="data/users/'.$notification['image'].'" /> New Message From <strong>"'.$notification['username'].'"</strong> in product:"'.substr($notification['Name'],0,5).'" ...</a></li>';
                            }
                        };  
                    ?>


                </ul> -->

            </div>



<?php 
                if(!empty($_SESSION['picture'])){
                    $picStatus = file_exists('data/users/'.$_SESSION['picture']);
        
                    if($picStatus){
                        echo '<img class="my-image img-thumbnail img-circle" src="'. 'data/users/'.$_SESSION['picture'] .'"  alt="img.png" />';

                    }else{
                        echo '<img class="my-image img-thumbnail img-circle" src="img.png" />';
                    }
                }else{
                    echo '<img class="my-image img-thumbnail img-circle" src="img.png" />';

                }
            ?>

<div class="btn-group my-info">
                <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo $_SESSION['username'] ?>
                    <span class="caret">
                    </span>
                </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="items.php">Add New Item</a></li>
                    <li><a href="mymessages.php">My Messages</a></li>
                    <li><a href="coupons.php">My Coupons</a></li>

                    <li><a href="logout.php">Logout</a></li>
                </ul>

            </div>
            
        </div>
        
    </div>
    
    <?php }?>

    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
                    aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="products.php">Homepage</a>
            </div>
            <div class="collapse navbar-collapse" id="app-nav">

            
                <ul class="nav navbar-nav navbar-right">
                    
                    <?php

                    $categorys = fetchAllFromTeble('id,Name,Description', 'categorys', 'WHERE Visibility = 1 LIMIT  5');

                    foreach ($categorys as $category) {
                        echo '<li>';
                        echo "<a href=\"products.php?categorys=" . $category['id'] . '&categoryName=' . $category['Name'] . '"/>';
                        echo '<span title="' . $category['Description'] . '">' . $category['Name'] . '</span>';
                        echo '</a>';
                        echo '</li>';
                    }

                ?>

                        <?php if (sqlCount('id','categorys') > 5){
                                    $categorys = fetchAllFromTeble('id,Name,Description', 'categorys', 'WHERE Visibility = 1');
                                    echo '<li class="dropdown">';
                                    echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">More<span class="caret"></span></a>';
                                    echo '<ul class="dropdown-menu">';
                                    foreach ($categorys as $index=>$category) {
                                        if($index > 5){
                                            echo '<li>';
                                            echo "<a href=\"products.php?categorys=" . $category['id'] . '&categoryName=' . $category['Name'] . '"/>';
                                            echo '<span title="' . $category['Description'] . '">' . $category['Name'] . '</span>';
                                            echo '</a>';
                                            echo '</li>';
                                        }
                                    }
                                    
                                    echo '</ul>';

                                    echo '</li>';
                        } ?>


                    <?php if(!isset($_SESSION['username'])){ 
                            echo '<li>
                                <a href="index.php">
                                                    <span title="Login Or Register To You\'r Account Now." class="badge badge-secondary">login/register</span>
                                                    </a>
                                </li>';

                    }?>


                    </ul>

                    </div>
        </div>


    </nav>

<script>
                                              // handling notificatations by localstorage
    // // handling Support Function's On LocalStorage
    // function getArray(arrayName){
    //     // check if exist ArrayName on The LocalStorage
    //     if(localStorage.getItem(arrayName) != null ){
    //         return JSON.parse(localStorage.getItem(arrayName));
    //     }else{
    //         let emty = [];
    //         localStorage.setItem(arrayName,JSON.stringify(emty));
    //         return emty;
    //     }
        
    // };
    // function addToArray(arrayName,value){
    //     // get arrayFrom LocalStorage
    //     if(arrayName && value && arrayName.length > 0 && value.toString().length > 0){
    //         let array = JSON.parse(localStorage.getItem(arrayName));
    //     if(!array.includes(value)){
    //         array.push(value);
    //         localStorage.setItem(arrayName,JSON.stringify(array));
    //     }

    //     }
    // };

    // function clearTable(arrayName){
    //     localStorage.setItem(arrayName,JSON.stringify([]));
    // }
    // var allNotifs = [];
    // // get all notification Ids then put Them Into an array;
    // document.querySelectorAll('#allNotifications li a').forEach(notifica => {
    //     allNotifs.push(parseInt(notifica.getAttribute('notificationid')));
    // })
    
    // if(!location.pathname.includes('mymessages.php')){
    //     if(allNotifs.length !== getArray('notifs').length){
    //     document.getElementById('notifDiv').style = 'color:red;';
    //     document.getElementById('notifStatus').innerText = 'New ';
    //     }
    // }else{
    //     allNotifs.forEach(noti=>{
    //         addToArray('notifs',noti);
    //     })
    // }

    // if(getArray('notifs').length > 10){
        
    // }

</script>