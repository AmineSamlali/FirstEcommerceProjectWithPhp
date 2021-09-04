<?php
ob_start();
session_start();
session_regenerate_id();

$pageName = 'Product';
include 'init.php';
if(isset($_SESSION['username'])){
	checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);
}
checkMaintenanceMode();

?>
<?php
// Check GET method
$work = true;
$check = 1;
if (!isset($_GET['itemid']) or empty($_GET['itemid']) or !is_numeric($_GET['itemid'])) {
    $work = false;
}else{
    $productId = $_GET['itemid'];

    $check = checkField('products', 'Name', [
        'filed_name' => 'product_id',
        'value' => clean($productId),
    ], $mode = 'available', 'AND Status != 0');
    
}


if ($check == 0) {

    $work = false;
}

if (!$work) {

    echo '<div class = "container" >';
    echo "<div style=\"margin-top:10px\" class=\"alert alert-danger \">There's no Such ID Or This Item Is Waiting Approval</div>";
    echo "<div style=\"margin-top: 10px\" class=\"alert alert-danger \">Redirecting On 3s</div>";
    echo "</div>";
    header('Refresh:3;url=profile.php');
    exit();
}

// $data = fetchMyColumn('Name,Description,Added_Date,Price,Country_Made,Category,Added_by,tags','products','product_id='.$productId,'data','One');
$connect = $conn->prepare("SELECT
        products.*,
        categorys.Name AS categoryName,
        categorys.id AS categorysId,
        users.username AS addedByWho
        FROM
        products
        INNER JOIN categorys ON
        categorys.id = products.Category
        INNER JOIN users ON
        users.user_id = products.Added_by
        AND products.product_id = ?
");

$connect->execute([$productId]);
$data = $connect->fetch();

$oldPrice = $data['Price'];
?>



<h1 class="text-center"><?php echo $data['Name']; ?></h1>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="<?php 
                if(filter_var($data['Image'],FILTER_VALIDATE_URL)){
                    echo $data['Image'];
                }else{
                    echo 'data/uploads/' . $data['Image'] ;

                }
            ?>" alt="">
		</div>
		<div class="col-md-9 item-info">
			<p><?php echo $data['Description']; ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span> : <?php echo $data['Added_Date']; ?> </li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span> : <span id='OldPrice'><?php echo $data['Price']; ?>$</span><span id="newPrice"></span> </li>
				<li>
					<i class="fa fa-building fa-fw"></i>
					<span>Made In</span> : <?php echo $data['Country_Made']; ?>				</li>
				<li>
					<i class="fa fa-tags fa-fw"></i>
					<span>Category</span> :<?php echo "<a href=\"products.php?categorys=" . $data['categorysId'] . '&categoryName=' . $data['categoryName'] . '">';?> <?php echo $data['categoryName'] .'</a>' ; ?>
				</li>


                <li>
					<i class="fa fa-user fa-fw"></i>
					<span>Added By</span> : <a href="<?php echo 'products.php?userproducts='.$data['Added_by'] ?>"> <?php echo $data['addedByWho'] ?> </a>
				</li>

				<li class="tags-items">
					<i class="fa fa-tags fa-fw"></i>
					<span>Tags</span> :
                    <?php
                        $tags = explode(',', str_replace(' ','', $data['tags']));
                        foreach ($tags as $tag) {
                            $lowerCaseTag = strtolower($tag);
                            echo "<a href=\"products.php?tagname=$lowerCaseTag\">$tag</a>";
                        }
                        ?>
                    </li>




                                            <li>
                                                <i class="fa fa-star fa-fw"></i>
                                            <span>Rating</span>: <?php $ratingList = [
                1 => 'New',
                2 => 'Like New',
                3 => 'Used',
                4 => 'Very Old',
            ];
            echo $ratingList[$data['Rating']];
            ?>
                                        </li>

             
                <!-- // contact seller onclick=>{
                    // pop up form to Send Message then => redirect to Messages Page;

                } -->
                <?php 
                    if(isset($_SESSION['user_id'])){
                        if($data['Added_by'] != $_SESSION['user_id']){
                            echo '<li>';
                            echo '<i class="fa fa-reply-all"></i>';
                            echo '<span>Contact Option\'s</a></span> : <a class="btn btn-success" href="message.php?prd='.$productId.'" >Contact Seller</a></a>';
                            echo '</li>';
                        };
                    };
                ?>
                
                <?php 
                    if (isset($_SESSION['user_id'])) {
                        if ($data['Added_by'] != $_SESSION['user_id']) {
                            echo '<li>';
                            echo '<i class="fa fa-reply-all"></i>';
                            echo '<span>Coupon Code</span> : <input type="text" id="inputCoupon" placeholder="Add Coupon Code">';
                            echo '</li>';
                        }
                    }
                        
                        ?>

                
			</ul>
		</div>
	</div>

    <?php
if (!isset($_SESSION['username'])) {
    ?>
	<hr class="custom-hr">
	<a href="index.php">Login</a> or <a href="index.php">Register</a> To Add Comment	

    <hr class="custom-hr">

    <?php
}?>

<div class="row">
<div class="col-md-offset-3">
<div class="add-comment">

    
    <?php 

        $connect = $conn->prepare("SELECT
        categorys.Comments
    FROM
        products
    INNER JOIN categorys ON
        categorys.id = products.Category
        AND products.product_id = ?");
    $connect->execute([$productId]);
    $allowComments = $connect->fetch();
    if($allowComments['Comments']){ 
        ?>
    <?php

if (isset($_SESSION['username'])) {
    ?>

				<h3>Add Your Comment</h3>
				<form action="<?php $_SERVER['PHP_SELF']?>" id="formComment" method="POST">
					<textarea name="comment" id="userComment" required maxlength="500"></textarea>
					<input class="btn btn-primary" type="submit" value="Add Comment">
				</form>
                <hr class="custom-hr">

				<!-- <div class="alert alert-success">Comment Added</div>			</div> -->

    <?php
}
?>
</div>

<?php }else{
    echo '<i class="text-muted">Comments Desibled On This Category</i>';
} ?>
		</div>
	</div>



    <?php
        $connetion = $conn->prepare("SELECT
                            comments.comment_text,
                            comments.comment_id,
                            comments.added_by,
                            users.username as username,
                            users.image as picture

                        FROM
                            comments
                        INNER JOIN users ON
                            users.user_id = comments.added_by AND comments.item_id=? ORDER BY comments.comment_id DESC");

        $connetion->execute([$productId]);
        $data = $connetion->fetchAll();
        echo '<div id="productComments">';
foreach ($data as $comment) {
    $deleteBtn = (isset($_SESSION['user_id']) and $_SESSION['user_id'] === $comment['added_by']) ? '<a class= "btn btn-danger" style="float:right;" commentId= ' . $comment['comment_id'] . ' onclick="deleteComment(this)" ><i class="fas fa-trash-alt"></i>Delete</a>' : '';
    $image = (isset($comment['picture']) and !empty($comment['picture'])) ? 'data/users/' . $comment['picture'] : 'img.png';
    echo '<div  class="comment-box">
                                <div class="row">
                                    <div class="col-sm-2 text-center">
                                        <img class="img-responsive img-thumbnail img-circle center-block" src="'.$image.'" alt="">
                                        ' . $comment['username'] . '</div>
                                    <div class="col-sm-10">
                                        <p class="lead">'
        . $comment['comment_text'] . $deleteBtn .
        '</p>
                                    </div>
                                </div>
                            </div>';
}
echo '</div>';
?>

<?php include $tpl . 'footer.php'?>


<script>
    $("#formComment").submit((e)=>{
        e.preventDefault();
        let commentValue = document.getElementById('userComment').value;
        $.post('admin/ajax_check.php',{
            formType:'addNewComment',
            commentValue : commentValue,
            currentProduct: <?php echo $productId ?>
        },(res)=>{
            if(res == 1){
                $('#productComments').load(document.URL +  ' #productComments');
                document.getElementById('userComment').value = '';
            }
        })
}
)

function deleteComment(e){
    let commentId = e.getAttribute('commentId');
    $.post('admin/ajax_check.php',{
            formType:'DeleteComment',
            commentId: commentId
        },(res)=>{
            if(res == 1){
                e.parentNode.parentNode.parentNode.remove();
            }
        })

}

if(document.getElementById('inputCoupon') != null){
    document.getElementById('inputCoupon').addEventListener('input',(e)=>{
    if(e.target.value.length >= 4){
        $.post('admin/ajax_check.php',{
            formType:'couponDo',
            couponVal:e.target.value,
            product_id:"<?php echo $productId; ?>"
        },(res) => {
            if(res.length > 0){
                let newPrice = Math.round((parseInt("<?php echo $oldPrice ?>") * parseInt(res)) / 100) 
                document.getElementById('inputCoupon').style = 'border-color:green;';
                document.getElementById('OldPrice').style = 'text-decoration: line-through';
                document.getElementById('newPrice').innerHTML = 'New Price is ' +'<strong>' + newPrice + '$</strong>'
            }else{
                document.getElementById('inputCoupon').style = 'border-color:red;';
                document.getElementById('OldPrice').style = '';
                document.getElementById('newPrice').innerHTML = ''

            }
        })
    };
})


}
</script>

<?php 
    ob_end_flush();

?>