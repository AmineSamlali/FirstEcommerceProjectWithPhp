<?php 

session_start();
    if(!isset($_SESSION['username'])){
        header('location:login.php');
        exit();
    }

$pageName = 'Add New Product';
include 'init.php';
checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);

?>

<?php 

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if(checkIssetFields($_POST , ['name' , 'description','price','country' , 'rating' , 'category' , 'tags','cash'])){
			$method = cleanListOfFieldes($_POST);
            if(!isset($_FILES['img'])){
                echo doAlert(0 , '' , 'Please Try Again!');
                exit();
            }else{

                if(checkImage('img','type')){
                    echo doAlert(0 , '' , 'Please Try Again! 12');
                    header('profile.php');
                    exit();    
                }

            }
			// check if there is the same product in the database;
			$check = $conn->prepare("SELECT * FROM shop.products WHERE Name = ? AND Added_by = ?");
			$check->execute([
				$method['name'],
				$_SESSION['user_id']				
			]);
			$check = $check->rowCount();
			if(!$check){

                $location = dirname(__FILE__) . "\data\uploads\\";
                $files = scandir(dirname(__FILE__) . "\data\uploads\\", SCANDIR_SORT_DESCENDING);
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

				$connection = $conn->prepare("INSERT INTO shop.products(Name,Description,Price,Country_Made,Rating,Category,Added_by,tags,Image,cash) VALUE(?,?,?,?,?,?,?,?,?,?)");
				$connection->execute([
					$method['name'],
					$method['description'],
					$method['price'],
					$method['country'],
					$method['rating'],
					$method['category'],
					$_SESSION['user_id'],
					$method['tags'],
                    $product_image,
                    $method['cash']
				]);
				$res = $connection->rowCount();
                echo doAlert($res , 'Product Added' , 'Please Try Again!');

                if($res){
                    // delete the 'Cash' picture;
                    //get Cash id For that Product;
                    $listOfCash = explode(',',substr($method['cash'],0,-1));
                    outPutArray($listOfCash);
                    foreach ($listOfCash as $oneCash) {
                        $directory = dirname(__DIR__).'/shop/addProductCash/' . $oneCash;
                        unlink($directory);
                    }
                    //redirect to profile Page Agian
                    header('location:profile.php');

                }
			}else{
				echo doAlert(0 , '' , 'That Product is Already Added By You');

			}

		}
	}


?>


<h1 class="text-center"><?php echo $pageName ?></h1>

<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $pageName ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" enctype="multipart/form-data"  action="<?php echo $_SERVER['PHP_SELF'] ?>"
                            method="POST">
                            <!-- Start Name Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-10 col-md-9">
                                    <input pattern=".{4,}" title="This Field Require At Least 4 Characters" type="text"
                                        name="name" class="form-control live" placeholder="Name of The Item"
                                        data-class=".live-title" required />
                                </div>
                            </div>
                            <!-- End Name Field -->
                            <!-- Start Description Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input pattern=".{10,}" title="This Field Require At Least 10 Characters"
                                        type="text" name="description" class="form-control live"
                                        placeholder="Description of The Item" data-class=".live-desc" required />
                                </div>
                            </div>
                            <!-- End Description Field -->
                            <!-- Start Price Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="number" name="price" class="form-control live"
                                        placeholder="Price of The Item" data-class=".live-price" required />
                                </div>
                            </div>
                            <!-- End Price Field -->
                            <!-- Start Country Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Country</label>
                                <div class="col-sm-10 col-md-9">
                                    <input type="text" name="country" class="form-control" placeholder="Country of Made"
                                        required />
                                </div>
                            </div>
                            <!-- End Country Field -->
                            <!-- Start Status Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Rating</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="rating" required>
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Very Old</option>
                                    </select>
                                </div>
                            </div>
                            <!-- End Status Field -->
                            <!-- Start Categories Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="category" required>
                                        <option value="">...</option>
                                        <?php 
											$data = fetchAllFromTeble('id,Name','shop.categorys');
											
											foreach($data as $option){
												echo "<option value=".$option['id'].">".$option['Name']."</option>";
											};			
										?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Categories Field -->
                            <!-- Start Tags Field -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-sm-10 col-md-9">
                                    <input id="myTags" type="text" name="tags" class="form-control"
                                        placeholder="Separate Tags With Comma (,)" required />
                                </div>
                            </div>

                            <!-- End Tags Field -->


                            <div class="form-group form-group-lg">
                                <label class="col-sm-3 control-label">Upload Image</label>
                                <div class="col-sm-10 col-md-9">
										<input type="file" id="uploadImagId" class="form-control" name="img" accept="image/*" required>
                                </div>
                            </div>

                                    <input type="hidden" name="cash" value="" id="cash" />


                            <!-- Start Submit Field -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
                                </div>
                            </div>
                            <!-- End Submit Field -->
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <span class="price-tag">
                                $<span class="live-price">0</span>
                            </span>
                            <img class="img-responsive" id="realImage" style="width: auto;height: auto"  src="img.png" alt=""/>
                            <div class="caption">
                                <h3 class="live-title">Title</h3>
                                <p class="live-desc">Description</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>




<?php include $tpl.'footer.php'; ?>


<script>
    var cash = '';

    $('#uploadImagId').on('change', function (e) {
    let files = new FormData(), // you can consider this as 'data bag'
        url = 'admin/ajax_check.php';

    files.append('fileName', $('#uploadImagId')[0].files[0]); // append selected file to the bag named 'file'
    files.append('formType', 'showImageImage'); // append selected file to the bag named 'file'

    $.ajax({
        type: 'post',
        url: url,
        processData: false,
        contentType: false,
        data: files,
        success: function (response) {
            if(response){
                var imageName = response.replace(/^.*[\\\/]/, '');
                document.getElementById('realImage').src = response;
                cash += imageName.toLowerCase()+',';
                document.getElementById('cash').value = cash;
            }
        }
    });

    }
    

);

</script>
