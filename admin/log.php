<?php 
    session_start();
    include 'check_auth.php';

    $pageName = 'Products';
    include 'urls.php';
    include 'connect.php';
    include 'init.php';
    include $functions_directory . 'sql_functions.php';
?>

<style>
    .main{

        border: 1px solid #666;
    }
    
</style>
    <div class="container">
        <h1 class="text-center">Log</h1>

<div class="main">
        <ul class="list-unstyled latest-users">
            <?php
                $connection = $conn->prepare('SELECT * FROM shop.log ORDER BY log_datetime DESC');
                $connection->execute();
                $data = $connection->fetchAll();

                foreach($data as $row){
                    echo '<li>' .$row['log_text']  . '<span class = "text-muted"> At '. $row['log_datetime'] . ' </span>' .  '</li>';
                }
            ?>
        </ul>
    </div>
    <button class="btn btn-primary" style="float:right;margin-bottom: 3e20;margin-top: 5px;">Export</button>

    </div>



<?php include $template_directory .'footer.php' ?>

