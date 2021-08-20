<?php 
session_start();


$pageName = 'Home Page';
include 'init.php';
if(isset($_SESSION['username'])){
	checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);
}
checkMaintenanceMode();

?>

<?php
	// check if there is a Category in path;
	if(isset($_GET['categorys']) and !empty($_GET['categorys'])){
		$check = checkField('shop.categorys', 'Name', [
			'filed_name' => 'id',
			'value' => clean($_GET['categorys']),
		]);
		if(!$check){
			header('location:products.php');
			exit();
		}
		$allItems = fetchAllFromTeble('*','shop.products','WHERE Status = 1 AND Category='.$_GET['categorys'] .' ORDER BY product_id DESC');
	}elseif(isset($_GET['userproducts']) and !empty($_GET['userproducts'])){
		$check = checkField('shop.users', 'username', [
			'filed_name' => 'user_id',
			'value' => clean($_GET['userproducts']),
		]);
		if(!$check){
			header('location:products.php');
			exit();
		}
		$allItems = fetchAllFromTeble('*','shop.products','WHERE Status = 1 AND Added_by='.$_GET['userproducts'].' ORDER BY product_id DESC');
	}elseif(isset($_GET['tagname']) and !empty($_GET['tagname'])){
		$tagname = $_GET['tagname'];
		$allItems = fetchAllFromTeble('*','shop.products',"WHERE Status = 1 AND tags like '%$tagname%'".' ORDER BY product_id DESC');

	}else{
		$allItems = fetchAllFromTeble('*','shop.products','WHERE Status = 1'.' ORDER BY product_id DESC');
	}
?>

<div class="container">
	<div class="row">
		<?php
		if(!count($allItems)){
			echo "<h1 class='text-center'>Sorry there'is No Product</h1>";
		}
			foreach ($allItems as $item) {
				echo '<div class="col-sm-6 col-md-3" style ="margin-top:10px">  ';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">$' . $item['Price'] . '</span>';
						//"data/uploads/<?php echo $data['Image'] "
						echo '<img class="img-responsive" style="width: 250px;height: 250px;" src="data/uploads/'.$item['Image'].'" alt="" />';
						echo '<div class="caption">';
							echo '<h3><a href="product.php?itemid='. $item['product_id'] .'">' . $item['Name'] .'</a></h3>';
							echo '<p>' . $item['Description'] . '</p>';
							echo '<div class="date">' . $item['Added_Date'] . '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
	</div>
</div>


<?php include  $tpl . 'footer.php' ?>