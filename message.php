<?php 
    ob_start();
    session_start();
    session_regenerate_id();
    if(!isset($_SESSION['username'])){
        header('location:index.php');
        exit();
    }

    if(!isset($_GET['prd']) or empty($_GET['prd']) or !is_numeric($_GET['prd'])){
        header('location:products.php');
        exit();
    }; 

    $pageName = 'Add New Product';
    include 'init.php';
    checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);
    checkMaintenanceMode();

    $check = checkField('products', 'Name', [
        'filed_name' => 'product_id',
        'value' => clean($_GET['prd']),
    ], $mode = 'available', 'AND Status != 0');
    if(!$check){
        header('location:products.php');
        exit();
    }
    // handling admin
    $checkCurrentSession = $conn->prepare("SELECT product_id FROM products WHERE product_id = ? AND Added_by = ?");
    $checkCurrentSession->execute([ $_GET['prd'] ,$_SESSION['user_id'] ]);
    $stuts = $checkCurrentSession->rowCount();
    if($stuts){
        header('location:mymessages.php');
        exit();
    };


    $connection = $conn->prepare("SELECT * FROM messages WHERE added_by = ? AND added_to = ?");
    $connection->execute([$_SESSION['user_id'],$_GET['prd']]);
    $data = $connection->rowCount();
    if($data){
        header('location:mymessages.php');
        exit();

    }

    $product = fetchMyColumn('product_id,Name,Description,Price,Added_Date,Image','products','product_id = "'.$_GET['prd'].'"','data','One');
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['message']) and !empty($_POST['message'])){


            $message = clean($_POST['message']);
            $added_by = $_SESSION['user_id'];
            $added_at = $_GET['prd'];
            $connetion = $conn->prepare("INSERT INTO messages(added_by,added_to) VALUES(?,?)");
            $connetion->execute([$added_by,$added_at]);
            $lastId = $conn->lastInsertId("message_id");
            $connetion2 = $conn->prepare("INSERT INTO messages_sms(message_main,sms_from,sms_text) VALUE(?,?,?)");
            $connetion2->execute([$lastId,$added_by,$message]);
            $res = $connetion->rowCount();
            if($res){
                header('location:mymessages.php');
                exit();
            }
        }
    }
    
?>



<h1 class="text-center">Contact Form</h1>

<div class="container">
    
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-8">
                    <div class="form h-100">
                        <h3>Contact Seller</h3></h3>
                        <form class="mb-5" method="POST" id="contactForm" name="contactForm">

                            <div class="row">
                                <div class="col-md-12 form-group mb-5">
                                    <label for="message" class="col-form-label">Message *</label>
                                    <textarea class="form-control" name="message" id="message" cols="30" rows="4" placeholder="Write your message" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input type="submit" value="Send Message" class="btn btn-primary rounded-0 py-2 px-4">
                                    <span class="submitting"></span>
                                </div>
                            </div> 
                        </form>

                        <div id="form-message-warning mt-4"></div>
                        <!-- <div id="form-message-success" > -->
                            <!-- Your message was sent, thank you! -->
                        <!-- </div> -->

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info h-100">
                        <h3>Acount Product</h3>
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">
                                $<span class="live-price"><?php echo $product['Price']; ?></span>
                            </span>
                            <img class="img-responsive" id="realImage" style="width: 410px;height:300px" src="<?php 
                            $url = $product['Image'];
                            if (filter_var($url, FILTER_VALIDATE_URL)) { 
                                echo $product['Image'];
                            }else{
                                echo 'data/uploads/'.$product['Image'];

                            }
                            ?>" alt="">
                            <div class="caption">
                                <a href="product.php?itemid=<?php echo $product['product_id']; ?>"><h3 class="live-title"><?php echo $product['Name']; ?></h3></a>
                                <p class="live-desc"><?php echo substr($product['Description'] , 0 , 60); ?> ...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




<?php include $tpl.'footer.php'; ?>
</body>

</html>
<?php ob_end_flush(); ?>
