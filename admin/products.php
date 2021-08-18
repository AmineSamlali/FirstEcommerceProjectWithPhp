<?php 
    session_start();
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
                            $connection = $conn->prepare("SELECT shop.products.* , shop.categorys.Name AS categortName , shop.users.username AS AddedBy FROM shop.products INNER JOIN shop.categorys ON shop.categorys.id = shop.products.Category INNER JOIN shop.users on shop.users.user_id = shop.products.Added_by");
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
                                                type="datetime-local" class="form-control"
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
var product = '';

function editeProduct(e) {
    let productId = e.getAttribute('productId');
    product = productId
    $.post('ajax_check.php', {
        formType: 'ShowInfoProduct',
        productId: productId
    }, (res) => {
        let data = JSON.parse(res);
        console.log(data);
        const newTime = (data[0]['Added_Date'].toLocaleString("sv-SE") + '').replace(' ', 'T');
        $('#productName').val(data[0]['Name']);
        $('#productDesc').val(data[0]['Description']);
        $('#productPrice').val(data[0]['price']);
        $('#productAddedDate').val(newTime);
        $('#productAddedBy').val(data[0]['Added_by']);
        $('#productCategory').val(data[0]['Category']);
        $('#productTags').val(data[0]['tags']);
        $('#productMade').val(data[0]['Country_Made']);
        $('#productStatus').val(data[0]['Status'])

    })
}

function deleteProduct(e) {
    let productId = e.getAttribute('productId');
    $.post('ajax_check.php', {
        formType: 'delete-product',
        'productId': productId
    }, (res) => {
        if (res == 1) {
            location.reload()
        }
    })
}

$('#newProduct').submit(function(e) {
    e.preventDefault();
    $.post('ajax_check.php', {
        formType: "addNewProduct",
        name: $('#productNameX').val(),
        description: $('#productDescX').val(),
        price: $('#productPriceX').val(),
        addedDate: $('#productAddedDateX').val(),
        addedBy: $('#productAddedByX').val(),
        category: $('#productCategoryX').val(),
        madeOn: $('#productMadeX').val(),
        tags: $('#productTagsX').val(),
        status:$('#productStatusX').val()
    }, (data) => {
        if (data == 1) {            
            location.reload()
        } else {
            alert("Please Try Again!")
        }

    })


})

$('#EditeProduct').submit(function(e) {
    e.preventDefault();
    $.post('ajax_check.php', {
        formType: "EditeProduct",
        product_Id: product,
        name: $('#productName').val(),
        description: $('#productDesc').val(),
        price: $('#productPrice').val(),
        addedDate: $('#productAddedDate').val(),
        addedBy: $('#productAddedBy').val(),
        category: $('#productCategory').val(),
        madeOn: $('#productMade').val(),
        tags: $('#productTags').val(),
        status:$('#productStatus').val()
    }, (data) => {
        console.log(data);
        if (data == 1) {
            location.reload();
        }
    });

});


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
    $.post('ajax_check.php', {
        formType: 'product-activate',
        product_id: productId
    }, (data) => {
        if (data == 1) {
            location.reload();
        }
    })
}

</script>