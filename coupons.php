<?php 

session_start();
    if(!isset($_SESSION['username'])){
        header('location:index.php');
        exit();
    }

$pageName = 'Coupon';
include 'init.php';
checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);
checkMaintenanceMode();

?>

<?php
    // handle add new coupon action
    if($_SERVER['REQUEST_METHOD'] === "POST"){
        if(checkIssetFields($_POST,['couponCode','productMain','expireDate','couponCodeProgress']) && checkFiledsLength($_POST,['couponCode','productMain','expireDate'])){
            // check if there is that coupon Code;
            $check = checkField('coupons','coupon_code',[
                'filed_name'=>'coupon_code',
                'value' => clean($_POST['couponCode'])
            ]);
            // check if that user has less then 5 coupons
            $count = sqlCount('coupon_code','coupons WHERE added_by = "'.$_SESSION['user_id'].'"');
            // check if that product Has No Coupons Code
            $count = sqlCount('coupon_code','coupons WHERE product_main = "'.$_POST['productMain'].'"');
            if(!$check && !$count){
                if($count <= 5){
                    if(strlen($_POST['couponCode']) >= 5){
                        $checkOptionValue = checkField('products','*',[
                            'filed_name'=>'product_id',
                            'value' => clean($_POST['productMain'])
                        ],'available','AND Added_by="'.$_SESSION['user_id'].'"');   
                        if($checkOptionValue){
                            $safeList = [1,2,3,4,5,6];
                            if(in_array(clean($_POST['expireDate']),$safeList) && is_numeric($_POST['couponCodeProgress']) && 100 >= $_POST['couponCodeProgress'] && $_POST['couponCodeProgress'] >= 1){
                                $expireDate = clean($_POST['expireDate']);
                                
                                // handling expire Date
                                $time = 0;
                                switch($expireDate){
                                    case 6:
                                        $time = 30;
                                        break;
                                    case 5:
                                        $time = 14;
                                        break;
                                    case 4:
                                        $time = 7;
                                        break;
                                    case 3:
                                        $time = 3;
                                        break;
                                    case 2:
                                        $time = 2;
                                        break;
                                    case 1:
                                        $time = 1;
                                        break;
                                };
                                $couponCode = clean($_POST['couponCode']);
                                $productMain = clean($_POST['productMain']);
                                $addedBy = $_SESSION['user_id'];
                                $expireDate = date('Y-m-d H:i:s', strtotime("+$time day", time() ));
                                $progressValue = $_POST['couponCodeProgress'];
                                
                                $connection = $conn->prepare("INSERT INTO coupons(coupon_code,product_main,added_by,new_price,expire) VALUES(?,?,?,?,?)");
                                $connection->execute([str_replace(' ','',$couponCode),$productMain,$addedBy,$progressValue,$expireDate]);
                                $res = $connection->rowCount();
                                if($res){
                                    echo '<script>alert("Coupon Added Successfully")</script>';
                                }else{
                                    echo '<script>alert("Please Try Again !")</script>';
                                }
                            }


                        }
                    }

                }else{
                    doAlert(1,'You Can\'t Add More Than 5 Coupons','');
                }
            }
        }
    }
?>



<h1 class="text-center">Create Coupon</h1>
</h1>
<div class="container">
    <div class="container categories">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> Manage Coupons
            </div>
            <div id="couponsContainer" class="panel-body">


                <?php 
                    $connection = $conn->prepare("SELECT 
                    coupons.* , 
                    products.product_id,
                    products.Name
                    FROM coupons
                    INNER JOIN products ON
                    coupons.product_main = products.product_id
                    WHERE coupons.added_by = ?
                    ");
                    $connection->execute([$_SESSION['user_id']]);
                    $data = $connection->fetchAll();
                    foreach($data as $coupon){
                        
                        $end = strtotime($coupon['expire']);
                        $start = strtotime(date("Y-m-d H:i:s"));
                        $days_between = ceil(abs($end - $start) / 86400);
                        $leftDate = $days_between;
                        if($start>=$end){
                            $connection2 = $conn->prepare("DELETE FROM coupons WHERE coupon_id = {$coupon['coupon_id']}");
                            $connection2->execute();
                        }else{
                            echo '<div class="cat">
                            <div class="hidden-buttons"><a
                                    href="" onclick="deleteCoupon(this)" couponid="'.$coupon['coupon_id'].'" class="confirm btn btn-xs btn-danger"><i
                                        class="fa fa-close"></i> Delete</a></div>
                            <h3>Coupon For:"<a href="product.php?itemid='.$coupon['product_id'].'">'. str_replace('"','',$coupon['Name']) .'</a>"</h3>
                            <div class="full-view">
                                <h2><center><input type="text" value="'. strtoupper($coupon['coupon_code']) .'"
                                        disabled /> <a><i class="fa fa-clipboard" aria-hidden="true"></i></a> </center></h2>
                                <p style="float:left;" >'. $leftDate .' Days left to expire</p>
                                <p style="float:right;" >User '.$coupon['used_times'].' Times</p>

                            </div>
                        </div>';
    
                        }
                    };
                ?>

                
                <hr>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" id="closeBtn" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Add New Coupons</h4>
                    </div>
                    <div class="modal-body">

                        <form method="POST" id="categoryForm">
                        <div class="form-group">
                            <label for="couponCode">Coupon Code</label>
                            <input name="couponCode" id="couponCode" type="text" class="form-control"
                                placeholder="Coupon Code" autocomplete="off" minlength="5" required>
                            <small id="editeCategoryStatus" class="form-text text-muted" hidden>this
                                Coupon already taken</small>
                        </div>

                        <!-- Added To Product -->
                        <label for="productAt">Added To Product</label>
                        <div class="form-group">

                        <select name="productMain" id="productAt" class="form-control"
                            placeholder="Add AddedBy To the product" autocomplete="off" required>
                            <option value="">...</option>
                            <?php 
                                $connection = $conn->prepare("SELECT product_id,Name FROM products WHERE Added_by = ? AND Status = 1");
                                $connection->execute([$_SESSION['user_id']]);
                                $data = $connection->fetchAll();
                                foreach($data as $product){
                                    $count = sqlCount('coupon_code','coupons WHERE product_main = "'.$product['product_id'].'"');
                                    if($count == 0){
                                        echo '<option value="'.$product['product_id'].'">'.$product['Name'].'</option>';
                                    };
                                }
                            ?>
                            
                        </select>
                        </div>
                        <div class="form-group">

                        <label for="expireDate">Expire At</label>
                        <select name="expireDate" id="expireDate" class="form-control"
                            placeholder="Add AddedBy To the product" autocomplete="off" required>
                            <option value="">...</option>
                            <option value="1">After 1 Day</option>
                            <option value="2">After 2 Day</option>
                            <option value="3">After 3 Day</option>
                            <option value="4">After 1 week</option>
                            <option value="5">After 2 week's</option>
                            <option value="6">After 1 mounth</option>
                        </select>

                        </div>
                        
                        <div class="form-group">
                            <label for="couponCode">Reduce the price to <span id="progValue"></span> %</label>
                            <input name="couponCodeProgress" id="couponCodeProg" type="range" class="form-control"
                                placeholder="Coupon Code" min="1" max="100" autocomplete="off" required>
                                
                            </div>

                        <center><br><input type="submit" id="btnAdd" class="btn btn-primary" value="Add Coupons"></center>

                        </form>

                    </div>
                </div>
            </div>

        </div>


        <?php 
            $count = sqlCount('product_id','products WHERE Added_by = "'.$_SESSION['user_id'].'"');
            $count1 = sqlCount('coupon_id','coupons WHERE added_by = "'.$_SESSION['user_id'].'"');

            if($count != $count1){
                echo '<a class="btn btn-primary" style="float:right;margin-bottom: 3e20;margin-bottom: 5px;" data-toggle="modal"
                data-target="#myModal"><i class="fa fa-plus"></i> Add New
                Coupon</a>';
            }

        ?>

        </div>
</div>

<?php include $tpl.'footer.php'; ?>

<script>
checkFileds('#couponCode', 5, 'admin/ajax_check.php', 'btnAdd', 'editeCategoryStatus', {
    fieldName: 'coupon_code',
    tableName: 'coupons'
});

    document.getElementById('progValue').innerText = document.getElementById('couponCodeProg').value;

    document.getElementById('couponCodeProg').addEventListener('change',(e) => {
        document.getElementById('progValue').innerText = e.target.value;
    })


function deleteCoupon(e){
    let couponId = e.getAttribute('couponid');
    $.post('admin/ajax_check.php',{
        formType:'delete-coupon',
        couponId:couponId
    },(res)=>{
        console.log(res)
    })
}

</script>



