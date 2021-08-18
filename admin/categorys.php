<?php 
    session_start();
    include 'check_auth.php';

    $pageName = 'Categorys';
    
    include 'urls.php';
    include 'connect.php';
    include 'init.php';
    include $functions_directory . 'sql_functions.php';
?>


<h1 class="text-center">All Categorys</h1>
<div id="userTable">
    <div class="container">

        <div class="table-responsive" id="fullTable">
            <a href="#" class="btn btn-primary" style="float:right;margin-bottom: 3e20;margin-bottom: 5px;"
                data-toggle="modal" data-target="#myModal">Add New
                Category</a>

            <table class="main-table text-center table table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Visibility</td>
                    <td>Comments</td>
                    <td>Ads</td>
                    <td>Options</td>
                </tr>

                <?php 
                
                $data = $conn->prepare("SELECT * FROM shop.categorys");
                $data->execute();

                foreach($data as $col){
                    $vis = $col['Visibility'] == 1 ? 'Yes' : 'No';
                    $comments = $col['Comments'] == 1 ? 'Yes' : 'No';
                    $ads = $col['Ads'] == 1 ? 'Yes' : 'No';
                    echo '<tr>';
                        echo '<td>'.$col['id'].'</td>';
                        echo '<td>'.$col['Name'].'</td>';
                        echo '<td>'.$col['Description'].'</td>';
                        echo '<td>'.$vis.'</td>';
                        echo '<td>'.$comments.'</td>';
                        echo '<td>'.$ads.'</td>';
                        echo '<td>
                        <a  data-toggle="modal" data-target="#CateModal" categoryId =' . $col['id'] . ' onclick = "editeCategory(this)"  class="btn btn-success"><i class="fas fa-user-edit"></i> Edite</a>
                        <a categoryId = '.$col['id'].' onclick = "deleteCategory(this)" class="btn btn-warning"><i class="fas fa-user-times"></i> Delete</a>';

                    echo '</tr>';
                    
                };


                ?>
                <!-- [START] startEite Form  -->
                <div class="modal fade" id="CateModal" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">

                                <form method="POST" id="categoryFormEdite">
                                    <div class="form-group">
                                        <label for="categoryName">Category Name</label>
                                        <input name="categoryNameEdite" id="categoryNameEdite" type="text" class="form-control"
                                            placeholder="Catgory Name" autocomplete="off" required>
                                        <small id="editeCategoryStatus" class="form-text text-muted" hidden>this
                                            Category already there</small>

                                    </div>

                                    <div class="form-group">
                                        <label for="categoryDesc">Description</label>
                                        <input name="categoryDescEdite" id="categoryDescEdite" type="text" class="form-control"
                                            placeholder="Add Description To the Category" autocomplete="off">
                                    </div>





                                    <!-- Visibilty -->
                                    <label>Visibilty</label>
                                    <div class="form-check">
                                        <input class="form-check-input" value="1" type="radio" id="flexRadioVisibilty"
                                            name="flexRadioVisibiltyEdite">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" value="0" type="radio" id="flexRadioVisibilty"
                                            name="flexRadioVisibiltyEdite">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>


                                    <!-- Commenets  -->

                                    <label>Allow Commenets</label>
                                    <div class="form-check">
                                        <input class="form-check-input" value="1" type="radio" id="flexRadioCommenets"
                                            name="flexRadioCommenetsEdite" >
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" value="0" type="radio" id="flexRadioCommenets"
                                            name="flexRadioCommenetsEdite">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>

                                    <!-- Ads  -->
                                    <label>Allow Ads</label>
                                    <div class="form-check">
                                        <input class="form-check-input" value="1" type="radio" id="flexRadioAds"
                                            name="flexRadioAdsEdite" >
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" value="0" type="radio" id="flexRadioAds"
                                            name="flexRadioAdsEdite">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>

                                    <center><input type="submit" id="btnAdd" class="btn btn-primary"
                                            value="Edite Category"></center>


                                </form>
                            </div>
                        </div>

                    </div>

                </div>




                <!-- add New Category -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" id="closeBtn" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add New Category</h4>
                            </div>
                            <div class="modal-body">

                                <form method="POST" id="categoryForm">
                                    <div class="form-group">
                                        <label for="categoryName">Category Name</label>
                                        <input name="categoryName" id="categoryName" type="text" class="form-control"
                                            placeholder="Catgory Name" autocomplete="off" required>
                                        <small id="editeCategoryStatus" class="form-text text-muted" hidden>this
                                            Category already there</small>

                                    </div>

                                    <div class="form-group">
                                        <label for="categoryDesc">Description</label>
                                        <input name="categoryDesc" id="categoryDesc" type="text" class="form-control"
                                            placeholder="Add Description To the Category" autocomplete="off">
                                    </div>





                                    <!-- Visibilty -->
                                    <label>Visibilty</label>
                                    <div class="form-check">
                                        <input class="form-check-input" value="1" type="radio" id="flexRadioVisibilty"
                                            name="flexRadioVisibilty" checked >
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" value="0" type="radio" id="flexRadioVisibilty"
                                            name="flexRadioVisibilty">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>


                                    <!-- Commenets  -->
                                    <label>Allow Commenets</label>
                                    <div class="form-check">
                                        <input class="form-check-input" value="1" type="radio" id="flexRadioCommenets"
                                            name="flexRadioCommenets" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" value="0" type="radio" id="flexRadioCommenets"
                                            name="flexRadioCommenets">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>

                                    <!-- Ads  -->
                                    <label>Allow Ads</label>
                                    <div class="form-check">
                                        <input class="form-check-input" value="1" type="radio" id="flexRadioAds"
                                            name="flexRadioAds" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" value="0" type="radio" id="flexRadioAds"
                                            name="flexRadioAds">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            No
                                        </label>
                                    </div>


                                    <center><input type="submit" id="btnAdd" class="btn btn-primary"
                                            value="Add Category"></center>

                                </form>

                            </div>
                        </div>

                    </div>

                </div>
            </table>
        </div>
    </div>

</div>

<?php include $template_directory .'footer.php' ?>





<script>
checkFileds('#categoryName', 0, 'ajax_check.php', 'btnAdd', 'editeCategoryStatus', {
    fieldName: 'Name',
    tableName: 'shop.categorys'
});

let categoryId = '';

function editeCategory(e) {
    let cateId = e.getAttribute('categoryId');
    categoryId = cateId;
    $.post('ajax_check.php', {
        formType: 'ShowInfoCategory',
        cateId: cateId
    }, (data) => {
        console.log(data);

        let res = JSON.parse(data);
        $('input[name=\'categoryNameEdite\']').val(res['Name']);
        $('input[name=\'categoryDescEdite\']').val(res['Description']);

        document.getElementsByName('flexRadioVisibiltyEdite').forEach(input => {
            if(input.value == res['Visibility']) {
                input.setAttribute('checked' , '');
            };
        })
        document.getElementsByName('flexRadioCommenetsEdite').forEach(input => {
            if(input.value == res['Comments']) {
                input.setAttribute('checked' , '');
            };
        })
        document.getElementsByName('flexRadioAdsEdite').forEach(input => {
            if(input.value == res['Ads']) {
                input.setAttribute('checked' , '');
            };
        })
    });

}

$('#categoryFormEdite').submit(function (e){
    e.preventDefault();
    $.post('ajax_check.php', {
        formType: 'EditeCategory',
        cateId:categoryId,
        categoryName: $('input[name=\'categoryNameEdite\']').val(),
        categoryDesc: $('input[name=\'categoryDesc\']').val(),
        flexRadioVisibility: $('input[name=\'flexRadioVisibiltyEdite\']:checked').val(),
        flexRadioComments: $('input[name=\'flexRadioCommenetsEdite\']:checked').val(),
        flexRadioAds: $('input[name=\'flexRadioAdsEdite\']:checked').val()
    }, (data) => {

        location.reload();

    })
})


$("#categoryForm").submit(function(e) {
    e.preventDefault();
    $.post('ajax_check.php', {
        formType: 'addNewCategory',
        categoryName: $('#categoryName').val(),
        categoryDesc: $('#categoryDesc').val(),
        flexRadioVisibility: $('input[name=\'flexRadioVisibilty\']:checked').val(),
        flexRadioComments: $('input[name=\'flexRadioCommenets\']:checked').val(),
        flexRadioAds: $('input[name=\'flexRadioAds\']:checked').val()
    }, (data) => {
            location.reload();
    })
})


function deleteCategory(e){
    let cateId = e.getAttribute('categoryId');
    $.post('ajax_check.php' , {formType:'delete-cate' , 'cateId':cateId} , (res) => {
            location.reload()
    })
}



</script>