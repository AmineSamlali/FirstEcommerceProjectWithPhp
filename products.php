<?php 
session_start();
session_regenerate_id();


$pageName = 'Home Page';
include 'init.php';
if(isset($_SESSION['username'])){
	checkUserStatus($_SESSION['username'],sha1($_SESSION['password']),'',true);
}
checkMaintenanceMode();

?>

<style>

.pagination {
    display: inline-block;
}

.pagination a {
    color: black;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
}

.pagination a.active {
    background-color: #4CAF50;
    color: white;
    border: 1px solid #4CAF50;
}

.pagination a:hover:not(.active) {
    background-color: #ddd;
}

.pagination a:first-child {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.pagination a:last-child {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}
</style>

</style>



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
	$jsAllproduct = json_encode($allItems);
?>






<div class="container">
	<div id="status" class="text-center"></div>
    <div class="row justify-content-start" >
		<div id="allProducts">

		</div>

		</div>



    <center>
        <nav aria-label="Page navigation example" id="paginationBar">
            <ul class="pagination" id="paginationAll">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous" id="prev" type="prev"
                        onclick="pagination(this)">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php 		
                        $allProduct = count($allItems);
                            
                        $shopPorduct = 8;
                        $numbersOfPages = ceil($allProduct / $shopPorduct);
                        for ($i = 1 ; $i <= $numbersOfPages ; $i++) {
                            echo"<li onclick=\"pagination(this)\" class=\"page-item\"><a class=\"\" >$i</a></li>";
                        }
						?>

                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next" type="next" id="next" onclick="pagination(this)">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </center>


            <script>

function isURL(string) {
    let url;

    try {
        url = new URL(string);
    } catch (_) {
        return false;  
    }

    return url.protocol === "http:" || url.protocol === "https:";
}
	var numbersOfPages = "<?php echo $numbersOfPages ?>";
	var allitems = JSON.parse(`<?php echo $jsAllproduct ?>`);
	var numberShow = "<?php echo $shopPorduct?>";
	var currentPage = 1;
	if(allitems.length === 0){
		let h1 = document.createElement('h1')
		h1.classList.add('text-center')
		h1.innerText = 'There is No product Here!'
		document.getElementById("status").appendChild(h1)
		document.getElementById('paginationBar').setAttribute('hidden','')
	}

	setProducts();
	function setProducts(currentPage = 1){
		document.getElementById('allProducts').innerHTML = '';
		let newOne = allitems.slice((parseInt(currentPage)*parseInt(numberShow)) - parseInt(numberShow) , (currentPage*parseInt(numberShow)+parseInt(numberShow)) - parseInt(numberShow))
		for(let i = 0 ; i < newOne.length ; i++){
			var image = '';
            if(isURL(newOne[i]['Image']) === true){
                image = newOne[i]['Image'];
            }else{
                image = 'data/uploads/' + newOne[i]['Image']

            }
			let divContent = document.createElement('div');
			divContent.innerHTML = `
				<div class="col-sm-6 col-md-3" style="margin-top:10px">
				<div class="thumbnail item-box"><span class="price-tag">$${newOne[i]['Price']}</span><img class="img-responsive"
							style="width: 250px;height: 250px;" src="${image}" alt="">
						<div class="caption">
							<h3><a href="product.php?itemid=${newOne[i]['product_id']}">${newOne[i]['Name']}</a></h3>${newOne[i]['Description'].substring(0,80)} ...<div
								class="date">${newOne[i]['Added_Date']}</div>
						</div>
					</div>
			</div>
		`
		document.getElementById('allProducts').appendChild(divContent);
		}
	}


    function pagination(e) {
        if (e.hasAttribute('type')) {
            if (e.getAttribute('type') === 'next') {
                if (numbersOfPages == currentPage) {
                    currentPage = 1
                } else {
                    currentPage++
                }
            } else {
                if (currentPage == 1) {
                    currentPage = numbersOfPages
                } else {
                    currentPage--
                }
            }
        } else {
            currentPage = parseInt(e.innerText);
        }
        let list = document.querySelectorAll('#paginationAll li a');
        list.forEach(pagin => {
            if (pagin.innerText == currentPage) {
                pagin.className = 'active';
            } else {
                pagin.className = '';
            }
        })

		setProducts(currentPage);
    }
    </script>

</div>


<?php include  $tpl . 'footer.php' ?>