<?php 
    session_start();
    session_regenerate_id();

    include 'check_auth.php';

    $pageName = 'Products';

    include 'urls.php';
    include 'connect.php';
    include 'init.php';
    include $functions_directory . 'sql_functions.php';
?>



<h1 class="text-center">All Products</h1>
<div id="userTable">
    <div class="container">

        <div class="table-responsive">
            <a href="#" class="btn btn-primary" onclick="checkIfIs(this)" style="float:right;margin-bottom: 3e20;margin-bottom: 5px;"
                data-toggle="modal" data-target="#myModal">Add New
                Product</a>
                <input id="productSearch" placeholder="Filter Products By Name" type="text">

            <table class="main-table text-center table table-bordered">
                <tbody>
                    <tr>
                        <td>#ID</td>
                        <td>Product Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Added By</td>
                        <td>Tags</td>

                        <td>Control</td>
                    </tr>
                    <tr>
                        <?php
                        // SELECT products.* , categorys.Name AS categortName , users.username AS AddedBy FROM products INNER JOIN categorys ON categorys.id = products.Category INNER JOIN users on users.user_id = products.Added_by
                            $connection = $conn->prepare("SELECT shop.products.* , shop.categorys.Name AS categortName , shop.users.username AS AddedBy FROM shop.products INNER JOIN shop.categorys ON shop.categorys.id = shop.products.Category INNER JOIN shop.users on shop.users.user_id = shop.products.Added_by ORDER BY `product_id` DESC");
                            $connection->execute();
                            $data = $connection->fetchAll();
                            foreach($data as $product){
                                echo '<tr>';
                                echo '<td>'.$product['product_id'].'</td>';
                                echo '<td>'.$product['Name'].'</td>';
                                echo '<td>'.$product['Description'].'</td>';
                                echo '<td>'.$product['Price'].'</td>';
                                echo '<td>'.$product['Added_Date'].'</td>';
                                echo '<td>'.$product['categortName'].'</td>';
                                echo '<td>'.$product['AddedBy'].'</td>';
                                echo '<td>'.$product['tags'].'</td>';
                                echo '<td>
                                    <a data-toggle="modal" data-target="#CateModal" productId ='.$product['product_id'].' onclick = "editeProduct(this)"  class="btn btn-success"><i class="fas fa-user-edit"></i> Edite</a>
                                    <a productId = '.$product['product_id'].' onclick = "deleteProduct(this)" class="btn btn-warning"><i class="fas fa-user-times"></i> Delete</a>';
                                    if ($product['Status'] == 0){
                                        echo '<a productActiveId='.$product['product_id'].' style="margin-top: 3px;" onclick="activeProduct(this)" class="btn btn-info"><i class="fas fa-user-check"></i> Activate</a>';
                                    }
    
                                    echo'</td>';
                                echo '</tr>';
                            }
                        ?>

                    </tr>


                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">

                                    <form method="POST" id="newProduct">

                                        <div class="form-group">
                                            <label for="categoryName">product Name</label>
                                            <input name="productNameEdite" id="productNameX" type="text"
                                                class="form-control" placeholder="product Name" autocomplete="off"
                                                required>
                                            <small id="editeproductStatus" class="form-text text-muted" hidden>this
                                                product already there</small>

                                        </div>

                                        <div class="form-group">
                                            <label for="productDesc">Description</label>
                                            <input name="productDescEdite" id="productDescX" type="text"
                                                class="form-control" placeholder="Add Description To the product"
                                                autocomplete="off" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="productPrice">Price</label>
                                            <input name="productPriceEdite" id="productPriceX" type="number"
                                                class="form-control" placeholder="Add Price To the product" step="any"
                                                min="1" autocomplete="off" required>

                                        </div>
                                        <div class="form-group">
                                            <label for="productDesc">Added_Date</label>
                                            <input name="productAddedDateEdite" id="productAddedDateX"
                                                type="datetime-local" class="form-control"
                                                placeholder="Add Description To the product" autocomplete="off"
                                                required>
                                        </div>

                                        <div class="form-group">

                                            <label for="productAddedBy">Added By</label>
                                            <select name="productAddedByEdite" id="productAddedByX" class="form-control"
                                                placeholder="Add AddedBy To the product" autocomplete="off" required>
                                                <?php 
                                                    $connection = $conn->prepare("SELECT user_id,username FROM shop.users WHERE group_id != 1");
                                                    $connection->execute();
                                                    $data = $connection->fetchAll();
                                                    foreach($data as $user){
                                                        echo '<option value="'.$user['user_id'].'">'.$user['username'].'</option>';
                                                    }
                                                ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productCategory">Category</label>
                                            <select name="productCategoryEdite" id="productCategoryX"
                                                class="form-control" placeholder="Add Category To the product"
                                                autocomplete="off" required>
                                                <?php 
                                                    $connection = $conn->prepare("SELECT id,Name FROM shop.categorys");
                                                    $connection->execute();
                                                    $data = $connection->fetchAll();
                                                    foreach($data as $user){
                                                        echo '<option value="'.$user['id'].'">'.$user['Name'].'</option>';
                                                    }
                                                ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productDesc">Tags</label>
                                            <input name="productTagsEdite" id="productTagsX" type="text"
                                                class="form-control"
                                                placeholder="Add Tags To the product you should split them with comma (,)"
                                                autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="productDesc">Made On</label>
                                            <input name="productTagsEdite" id="productMadeX" type="text"
                                                class="form-control" placeholder="Add Made Country" autocomplete="off"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="productStatus">Product Status</label>
                                            <select name="productStatusEdite" id="productStatusX"
                                                class="form-control" placeholder="Add Status To the product"
                                                autocomplete="off" required>
                                                
                                                <option value="0">Off</option>
                                                <option value='1'>On</option>
                                            </select>
                                        </div>  
                                        <div class="form-group">
                                            <label for="productRating">Product Rating</label>
                                            <select name="productRatingEdite" id="productRatingX"
                                                class="form-control" placeholder="Add Rating To the product"
                                                autocomplete="off" required>
                                                <option value="">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="productImageX">Product Image</label>
                                            <input name="productTagsEdite" id="productImageX" accept="*/image" type="file"
                                                class="form-control"
                                                required>
                                        </div>

<!-- 
                                        <div class="form-group">
                                            <label for="productPicture">Add Picture</label>
                                            <input type="file" accept="*/image" name="img" class="form-control" id="productPicture" required>
                                        </div>   -->


                                        <center><input type="submit" id="btnAdd" class="btn btn-primary"
                                                value="Add Product"></center>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="modal fade" id="CateModal" role="dialog">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" id="EditeProduct">
                                        <div class="form-group">
                                            <label for="categoryName">product Name</label>
                                            <input name="productNameEdite" id="productName" type="text"
                                                class="form-control" placeholder="Catgory Name" autocomplete="off"
                                                required>
                                            <small id="editeproductStatus" class="form-text text-muted" hidden>this
                                                product already there</small>

                                        </div>

                                        <div class="form-group">
                                            <label for="productDesc">Description</label>
                                            <input name="productDescEdite" id="productDesc" type="text"
                                                class="form-control" placeholder="Add Description To the product"
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="productPrice">Price</label>
                                            <input name="productPriceEdite" id="productPrice" type="number"
                                                class="form-control" placeholder="Add Price To the product" step="any"
                                                min="1" autocomplete="off">

                                        </div>
                                        <div class="form-group">
                                            <label for="productDesc">Added_Date</label>
                                            <input name="productAddedDateEdite" id="productAddedDate"
                                                type="datetime-local" step="any" class="form-control"
                                                placeholder="Add Description To the product" autocomplete="off">
                                        </div>

                                        <div class="form-group">

                                            <label for="productAddedBy">Added By</label>
                                            <select name="productAddedByEdite" id="productAddedBy" class="form-control"
                                                placeholder="Add AddedBy To the product" autocomplete="off">
                                                <?php 
                                                    $connection = $conn->prepare("SELECT user_id,username FROM shop.users WHERE group_id != 1");
                                                    $connection->execute();
                                                    $data = $connection->fetchAll();
                                                    foreach($data as $user){
                                                        
                                                        echo '<option value="'.$user['user_id'].'">'.$user['username'].'</option>';

                                                    }
                                                ?>

                                            </select>
                                        </div>



                                        <div class="form-group">
                                            <label for="productCategory">Category</label>
                                            <select name="productCategoryEdite" id="productCategory"
                                                class="form-control" placeholder="Add Category To the product"
                                                autocomplete="off">
                                                <?php 
                                                    $connection = $conn->prepare("SELECT id,Name FROM shop.categorys");
                                                    $connection->execute();
                                                    $data = $connection->fetchAll();
                                                    foreach($data as $user){
                                                        echo '<option value="'.$user['id'].'">'.$user['Name'].'</option>';
                                                    }
                                                ?>

                                            </select>
                                        </div> 
                                        <div class="form-group">
                                            <label for="productDesc">Tags</label>
                                            <input name="productTagsEdite" id="productTags" type="text"
                                                class="form-control"
                                                placeholder="Add Tags To the product you should split them with comma (,)"
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label for="productDesc">Made On</label>
                                            <input name="productTagsEdite" id="productMade" type="text"
                                                class="form-control" placeholder="Add Made Country" autocomplete="off"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="productStatus">Product Status</label>
                                            <select name="productStatusEdite" id="productStatus"
                                                class="form-control" placeholder="Add Status To the product"
                                                autocomplete="off" required>
                                                
                                                <option value="0">Off</option>
                                                <option value='1'>On</option>

                                            </select>
                                        </div> 


                                        <div class="form-group">
                                            <label for="productRating">Product Rating</label>
                                            <select name="productRatingEdite" id="productRating"
                                                class="form-control" placeholder="Add Rating To the product"
                                                autocomplete="off" required>
                                                <option value="">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </select>
                                        </div>
                                        <img style="height: 80px;width:80px;" class="img-responsive img-thumbnail img-circle center-block" src="" id="curretImage">

                                        <div class="form-group">
                                            <label for="productRating">Product Image</label>
                                            <input name="img" id="productImage" accept="*/image" type="file"
                                                class="form-control"
                                                autocomplete="off" value="img.png" title="Leave it,if you don't Wanna change the image">
                                        </div> 

                                        <center><input type="submit" id="btnAddEdite" class="btn btn-primary"
                                                value="Edite Product"></center>
                                    </form> 
                                </div>
                            </div>

                        </div>

                    </div>

                </tbody>
            </table>
        </div>

    </div>

</div>



<?php include $template_directory .'footer.php' ?>




<script>


$(document).ready(function(){
let queryString = location.search;
if(queryString){
    const urlParams = new URLSearchParams(queryString);

if(urlParams.get('product').length > 0){
    let searchValue = urlParams.get('product');
    let table = document.querySelector("tbody");
    let childrens = Array.from(table.children);
    childrens.forEach(row => {
        let rowList= Array.from(row.children);
        if(rowList.length > 0){
            if(!(rowList[0].innerText == searchValue) && rowList[0].innerText != "#ID"){
                row.style = 'visibility:collapse';
            }else{
                row.style = 'visibility:visible';
            }
        }
    })
}
}
})


function isValidURL(string) {
  var res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
  return (res !== null)
};

var product = '';

function editeProduct(e) {
    let productId = e.getAttribute('productId');
    product = productId
    $.post('ajax_check.php', {
        formType: 'ShowInfoProduct',
        productId: productId
    }, (res) => {
        let data = JSON.parse(res);
        const newTime = (data[0]['Added_Date'].toLocaleString("sv-SE") + '').replace(' ', 'T');
        $('#productName').val(data[0]['Name']);
        $('#productDesc').val(data[0]['Description']);
        $('#productPrice').val(data[0]['price']);
        $('#productAddedDate').val(newTime);
        $('#productAddedBy').val(data[0]['Added_by']);
        $('#productCategory').val(data[0]['Category']);
        $('#productTags').val(data[0]['tags']);
        $('#productMade').val(data[0]['Country_Made']);
        $('#productStatus').val(data[0]['Status']);
        $('#productRating').val(data[0]['Rating']);
        if(isValidURL(data[0]['Image'])){
            $('#curretImage').attr('src' , data[0]['Image']);
        }else{
            $('#curretImage').attr('src' , window.location.origin +"/server/shop/data/uploads/" + data[0]['Image']);

        }
    })
}

function deleteProduct(e) {
    let productId = e.getAttribute('productId');
    $.post('ajax_check.php', {
        formType: 'delete-product',
        'productId': productId
    }, (res) => {
        console.log(res);
        if (res == 1) {
            location.reload()
        }
    })
}



$('#newProduct').on('submit', function (e) {
    e.preventDefault();
    let files = new FormData(), // you can consider this as 'data bag'
        url = 'ajax_check.php';
    files.append('formType', 'addNewProduct'); 
    files.append('name', $('#productNameX').val()); 
    files.append('description', $('#productDescX').val()); 
    files.append('price', $('#productPriceX').val()); 
    files.append('addedDate', $('#productAddedDateX').val()); 
    files.append('addedBy', $('#productAddedByX').val()); 
    files.append('category', $('#productCategoryX').val()); 
    files.append('madeOn', $('#productMadeX').val()); 
    files.append('tags', $('#productTagsX').val()); 
    files.append('status', $('#productStatusX').val()); 
    files.append('rating', $('#productRatingX').val()); 
    files.append('img', $('#productImageX')[0].files[0]); 
    $.ajax({
        type: 'post',
        url: url,
        processData: false,
        contentType: false,
        data: files,
        success: function (response) {

            if(response == 1){
                location.reload();
            }else{
                alert("Please Enter True Values");
            }

        }

    });
    }
);


$('#EditeProduct').on('submit', function (e) {
    e.preventDefault();
    let files = new FormData(), // you can consider this as 'data bag'
        url = 'ajax_check.php';
    files.append('formType', 'EditeProduct'); 
    files.append('product_Id', product); 
    files.append('name', $('#productName').val()); 
    files.append('description', $('#productDesc').val()); 
    files.append('price', $('#productPrice').val()); 
    files.append('addedDate', $('#productAddedDate').val()); 
    files.append('addedBy', $('#productAddedBy').val()); 
    files.append('category', $('#productCategory').val()); 
    files.append('madeOn', $('#productMade').val()); 
    files.append('tags', $('#productTags').val()); 
    files.append('status', $('#productStatus').val()); 
    files.append('rating', $('#productRating').val()); 
    files.append('img', $('#productImage')[0].files[0]); 
    $.ajax({
        type: 'post',
        url: url,
        processData: false,
        contentType: false,
        data: files,
        success: function (response) {
            if(response == 1){
                location.reload();
            }else{
                alert("Please Enter True Values");
            }

        }

    });
    }
);



function checkIfIs(e){
    $.post('ajax_check.php',{
        formType: "checkFor-user&categorys"
    },(data) => {
        if(data != 1){
            alert("You Can't add New Product,You Need at least One User And One Category !")
        }
    });
};



function activeProduct(e){
    let productId = e.getAttribute('productActiveId');
    $.post('ajax_check.php',{
        formType: 'product-activate',
        product_id: productId
    },(data) => {
        if (data == 1) {
            location.reload();
        }

    })
}

$(document).ready(function(){
    $("#productSearch").on('input', (e) => {
    let searchValue = e.target.value;
    let table = document.querySelector("tbody");
    let childrens = Array.from(table.children);
    childrens.forEach(row => {
        let rowList= Array.from(row.children);
        if(rowList.length > 0){
        if(!rowList[1].innerHTML.toLowerCase().startsWith(searchValue.toLowerCase()) && rowList[0].innerHTML != "#ID"){
            row.style = 'visibility:collapse';
        }else{
            row.style = 'visibility:visible';
            }
        }
    })

})

})


</script>

