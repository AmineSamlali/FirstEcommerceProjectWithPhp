<?php
session_start();

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

    $check = checkField('shop.products', 'Name', [
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

// $data = fetchMyColumn('Name,Description,Added_Date,Price,Country_Made,Category,Added_by,tags','shop.products','product_id='.$productId,'data','One');
$connect = $conn->prepare("SELECT
        shop.products.*,
        shop.categorys.Name AS categoryName,
        shop.categorys.id AS categorysId,
        shop.users.username AS addedByWho
        FROM
        shop.products
        INNER JOIN shop.categorys ON
        shop.categorys.id = shop.products.Category
        INNER JOIN shop.users ON
        shop.users.user_id = shop.products.Added_by
        AND shop.products.product_id = ?
");

$connect->execute([$productId]);
$data = $connect->fetch();

?>



<h1 class="text-center"><?php echo $data['Name']; ?></h1>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<img class="img-responsive img-thumbnail center-block" src="data/uploads/<?php echo $data['Image'] ?>" alt="">
		</div>
		<div class="col-md-9 item-info">
			<p><?php echo $data['Description']; ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="fa fa-calendar fa-fw"></i>
					<span>Added Date</span> : <?php echo $data['Added_Date']; ?>				</li>
				<li>
					<i class="fa fa-money fa-fw"></i>
					<span>Price</span> : <?php echo $data['Price']; ?>				</li>
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
				<li>

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
        shop.categorys.Comments
    FROM
        shop.products
    INNER JOIN shop.categorys ON
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
                            shop.comments.comment_text,
                            shop.comments.comment_id,
                            shop.comments.added_by,
                            shop.users.username as username,
                            shop.users.image as picture

                        FROM
                            shop.comments
                        INNER JOIN shop.users ON
                            shop.users.user_id = shop.comments.added_by AND shop.comments.item_id=? ORDER BY comments.comment_id DESC");

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
</script>


