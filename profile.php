<?php 
session_start();
    if(!isset($_SESSION['username'])){
        header('location:index.php');
        exit();
    }


$pageName = 'Profile';

include 'init.php';
checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);

checkMaintenanceMode();


?>

<?php 



    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(isset($_SESSION['password']) and isset($_SESSION['password2'])  and strlen($_POST['password']) >= 8 and strlen($_POST['password2']) >= 8){
            if(checkIssetFields($_POST , ['fullName','email','password','password2'])){

                $fullName = clean($_POST['fullName']);
                $email = clean($_POST['email']);
                $password = clean($_POST['password']);
                $password2 = clean($_POST['password2']);

                if($password === $password2){
                    
                    $connection = $conn->prepare('UPDATE shop.users SET `full_name` = ? , `email` = ? ,`password` = ? WHERE `user_id` = ? ');
                    $connection->execute([$fullName , $email,sha1($password),$_SESSION['user_id']]);

                    $_SESSION['full_name'] = $fullName;
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;

    
                }
                
            }
        }elseif(isset($_SESSION['fullName']) and isset($_SESSION['email']) and strlen($_POST['fullName']) >= 1 and strlen($_POST['email']) >= 1){
            if(checkIssetFields($_POST , ['fullName','email'])){
                
                $fullName = clean($_POST['fullName']);
                $email = clean($_POST['email']);
                $connection = $conn->prepare('UPDATE shop.users SET `full_name` = ? , `email` = ?  WHERE `user_id` = ? ');
                $connection->execute([$fullName , $email,$_SESSION['user_id']]);

                $_SESSION['full_name'] = $fullName;
                $_SESSION['email'] = $email;


            }
        }elseif(isset($_POST['updateImage']) AND !empty($_POST['updateImage'])){

            if(checkImage('img','name')){
                echo doAlert(1,'Please Enter A Valid Image','');
                header('location:profile.php');
            }
            if(!empty($_SESSION['picture'])){
                $directory = dirname(__DIR__).'\shop\data\users\\' . $_SESSION['picture'];
                unlink($directory);
    
            }
            $location = dirname(__FILE__) . "\data\users\\";

            $files = scandir(dirname(__FILE__) . "\data\users\\", SCANDIR_SORT_DESCENDING);

            $newest_file = $files[0];

            $produtImageName = intval(explode('.',$files[0])[0]);

            $produtImageName ++;

            $inputNameImage = $_FILES['img']['name'];

            $numberOftimes = substr_count($inputNameImage,'.') - 1;

            $inputNameImage = preg_replace('/\./','', $inputNameImage,$numberOftimes);
            
            $produtImageName = strval($produtImageName) . '.'. strtolower(explode('.',$inputNameImage)[1]);

            $uploadfile = $location . basename( $produtImageName );

            if(!move_uploaded_file($_FILES['img']['tmp_name'],$uploadfile)){
                echo doAlert(0,'','Please try again!');
            }

            $product_image = $produtImageName;
            

            $connection = $conn->prepare("UPDATE shop.users SET `image` = ?   WHERE `user_id` = ?");
            $connection->execute([$product_image,$_SESSION['user_id']]);

            $_SESSION['picture'] = $product_image;

        }
    }

?>

<h1 class="text-center">My Profile</h1>
<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span> : <?php echo $_SESSION['username'] ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email</span> : <?php echo $_SESSION['email'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name</span> : <?php echo $_SESSION['full_name'] ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Registered Date</span> : <?php echo $_SESSION['reg_date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Fav Category</span> : await
                    </li>
                </ul>
                <a data-toggle="modal" data-target="#myModal" class="btn btn-default">Edit Information</a>
            </div>
        </div>
    </div>
</div>

</div>


<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">My Items</div>
        <div class="panel-body">
            <div class="row">

            <?php             
                $products = fetchMyColumn('product_id,Name,Price,Description,Added_Date,Status,Image','shop.products',"Added_by = " .$_SESSION['user_id']. ' ORDER BY product_id DESC');
                if(!$products){
                    echo '<p class="col-sm-6">Sorry You Don\'t Have Any Products You can add it From <a href ="items.php" >Here</a>.</p>';
                }
                foreach($products as $product){
                    $status = $product['Status'];
                    echo '<div class="col-sm-6 col-md-3">';
                    echo '<div class="thumbnail item-box">';
                    if(!$status){
                        echo '<span class="approve-status">Waiting Approval</span>';
                    };
                    echo'<span class="price-tag">$121</span><img class="img-responsive" style="width: 250px;height: 250px;" src="data/uploads/' . $product['Image'] .'" alt="">';
                    echo '<div class="caption">';
                    echo '<h3><a href="product.php?itemid='.$product['product_id'].'">'.$product['Name'].'</a></h3>';
                    echo '<p>'.$product['Description'].'</p>';
                    echo '<div class="date">'.date("Y-m-d",strtotime($product['Added_Date'])).'</div>';
                    echo '</div></div></div>';

                }
            ?>

        </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edite My Profile</h4>
            </div>

            <div class="modal-body">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="categoryForm">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input name="fullName" id="fullName" value="<?php echo $_SESSION['full_name'] ?>" type="text"
                            class="form-control" placeholder="Catgory Name" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="email">email Name</label>
                        <input name="email" id="email" type="email" value="<?php echo $_SESSION['email'] ?>"
                            class="form-control" placeholder="Enter You'r Email Addrese" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="password" minlength="8" id="password" type="password" class="form-control"
                            placeholder="leave it blank if You don't wanna to change the password" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="password2">Re Password</label>
                        <input name="password2" minlength="8" id="password2" type="password" class="form-control"
                            placeholder="Renter You'r Password" autocomplete="off">
                    </div>

                    <center><input type="submit" id="btnAdd" class="btn btn-primary" value="Add Category"></center>

                </form>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                    <br>
                    <img style="height: 100px;width:100px;" class="img-responsive img-thumbnail img-circle center-block" src="<?php 
                        if(isset($_SESSION['picture']) and !empty($_SESSION['picture'])){
                            echo 'data/users/' . $_SESSION['picture'];
                        }else{
                            echo 'img.png';
                        }
                    ?>" alt="">
                    <br>
                    <div class="form-group">
                        
                    <input type="file"  class="form-control" name="img" accept="image/*" required>

                </div>


                    <center><input type="submit" name="updateImage" class="btn btn-primary" value="Update Picture"></center>

                </form>
            </div>
        </div>

    </div>

</div>



<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments In You Products</div>
            <div class="panel-body">
                <?php 
                $sql = "SELECT
                shop.comments.*,
                shop.products.Name AS productName,
                shop.products.product_id AS productId,

                shop.users.username AS userName
            FROM
                shop.comments
            INNER JOIN shop.products ON
                shop.products.product_id = shop.comments.item_id
            INNER JOIN shop.users ON
                shop.users.user_id = shop.comments.Added_by
            WHERE shop.products.Added_by = {$_SESSION['user_id']}
            ORDER BY
                comment_id
                DESC
            LIMIT 5";
                    $connection = $conn->prepare($sql);
                    $connection->execute();
                    $data =$connection->fetchAll();
                    if(!$data){
                        echo '<p class="col-sm-6">Sorry You Don\'t Have Any Comment</p>';
                    }
                foreach($data as $comment){
                        echo "<p  class='text'>Comment: \"{$comment['comment_text']}\" Added By \"{$comment['userName']}\" On Product:<a href='product.php?itemid={$comment['productId']}'>\"{$comment['productName']}\"</a> </p>";
                }
?>
        
            </div>
        </div>
    </div>
</div>

<?php include $tpl.'footer.php'; ?>


