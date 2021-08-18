<?php
session_start();
include 'check_auth.php';

$pageName = 'Dashboard';

include 'init.php';

include $functions_directory . 'sql_functions.php';
?>



<div class="home-stats">
    <div class="container text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                        Total Members
                        <span>
                            <a href="members.php"><?php echo sqlCount('username', 'shop.users WHERE user_id != 1'); ?></a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                        Pending Members
                        <span>
                            <a
                                href="members.php"><?php echo sqlCount('username', 'shop.users WHERE reg_status = 0 AND group_id != 1') ?></a>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                        Total Items
                        <span>
                            <a
                                href="members.php"><?php echo sqlCount('product_id', 'shop.products') ?></a>
                            </a>


                    </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comments"></i>
                    <div class="info">
                        Total Comments
                        <span>
                            <a href="products.php"><?php echo sqlCount('comment_id', 'shop.comments') ?></a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="latest">
    <div class="container">
        <div class="row">

        <div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i> Latest 5 Users
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
                                
                                        <?php 
										foreach(getLatest('user_id,username , registration_date','shop.users WHERE group_id != 1','user_id',5) as $value){
											echo "<li>" .'"'.$value['username'] .'"'."<small class = \"text-muted\"> Registered At ".$value['registration_date']."</small> <span class=\"btn btn-primary pull-right\" userid = ".$value['user_id']." data-toggle=\"modal\" data-target=\"#myModal\" onclick=showProfile(this) ><i class=\"far fa-eye\"></i> Show info</span></li>";
										}
									?>



                            </ul>
                                
                            </div>
						</div>
					</div>

                  

        <div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i> Latest 5 Products 
								<span class="toggle-info pull-right">
									<i class="fa fa-plus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">


                                    <?php 
                        $sql = "SELECT
                            shop.products.*,
                            shop.users.username AS userName
                        FROM
                            shop.products
                        INNER JOIN shop.users ON
                            shop.users.user_id = shop.products.Added_by
                            ORDER BY product_id DESC LIMIT 5";
                            $connect = $conn ->prepare($sql);
                            $connect->execute();
                            $data = $connect->fetchAll();
                            foreach($data as $product){
                                echo '<li>';
                                echo '<small class ="text-muted">Product: </small>'.$product['Name'] .  '<small class ="text-muted"> Added By : </small> <span title="'.$product['userName'].'">' . substr($product['userName'],0,8) .'</span>... <span class= "text-muted">'.$product['Added_Date'].'</span>';
                                echo '</li>';
                            
                        }
                        ?>

							
                            
                                </div>
						</div>
					</div>

    </div>

        <!-- Start Latest Comments -->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comments"></i>
                        Latest Comments
                        <span class="toggle-info pull-right">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body">


                    <?php 
                        $sql = "SELECT
                        shop.comments.*,
                        shop.products.Name AS productName,
                        shop.users.username AS userName
                    FROM
                        shop.comments
                    INNER JOIN shop.products ON
                        shop.products.product_id = shop.comments.item_id
                    INNER JOIN shop.users ON
                        shop.users.user_id = shop.comments.Added_by
                        ORDER BY comment_id LIMIT 5";

                        $connect = $conn ->prepare($sql);
                        $connect->execute();
                        $data = $connect->fetchAll();
                        foreach($data as $comment){
                            echo '<li>';
                            echo '<small class ="text-muted">Comment Added By: </small>'.$comment['userName'] .  '<small class ="text-muted"> In Product Name: </small> '. '<span title="'.$comment['productName'].'">' . substr($comment['productName'],0,15)  .'</span>...';
                            echo '</li>';
                            
                        }
                        ?>

                </div>
                </div>
            </div>
        </div>
        <!-- End Latest Comments -->
    </div>
</div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2>Member information's</h2>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="infoInputUsername">Username</label>
                    <input type="text" class="form-control" id="infoInputUsername" placeholder="Username" disabled>
                </div>
                <div class="form-group">
                    <label for="infoInputFullName2">Full Name</label>
                    <input type="text" class="form-control" id="infoInputFullName2" placeholder="Full Name" disabled>
                </div>
                <div class="form-group">
                    <label for="infoInputEmail">Email</label>
                    <input type="text" class="form-control" id="infoInputEmail" placeholder="Email" disabled>
                </div>
            </div>
        </div>

    </div>

</div>

<script >

function activeProduct(e){
    let productId = e.getAttribute('productActiveId');
    $.post('ajax_check.php', {
        formType: 'product-activate',
        product_id: productId
    }, (data) => {
        if (data == 1) {
            location.reload();
        }
    })
}


function showProfile(ev) {
    let userId = ev.getAttribute('userid');

    let infoUsername = document.getElementById('infoInputUsername');
    let infoFullName = document.getElementById('infoInputFullName2');
    let infoEmail = document.getElementById('infoInputEmail');
    $.post('ajax_check.php', {
        formType: 'showUserInfo',
        user_id: userId
    }, (data) => {
        let userInfos = JSON.parse(data)[0];
        console.log(data);
        infoUsername.value = userInfos['username'];
        infoFullName.value = userInfos['full_name'];
        infoEmail.value = userInfos['email'];
    })
}



</script>



<?php include $template_directory . 'footer.php'?>;