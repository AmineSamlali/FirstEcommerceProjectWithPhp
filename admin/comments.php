<?php 
    session_start();
    session_regenerate_id();

    include 'check_auth.php';

    $pageName = 'Categorys';
    
    include 'urls.php';
    include 'connect.php';
    include 'init.php';
    include $functions_directory . 'sql_functions.php';
?>

<h1 class="text-center">All Categorys</h1>

<div class="container">

        
        <input id="productSearch" placeholder="Filter Categorys By Name" type="text">

        <table id="categorysTable" class="main-table text-center table table-bordered">
            <tbody>
                <tr>
                    <td>#ID</td>
                    <td>Comment</td>
                    <td>Added By</td>
                    <td>Added To</td></td>
                    <td>Added At</td></td>
                    <td>comment_status</td>
                </tr>

                <?php 
                $connection = $conn->prepare("SELECT
                        comments.*,
                            shop.users.username,
                            shop.products.Name,
                            shop.products.product_id
                        FROM
                            shop.comments
                        INNER JOIN shop.users 
                        ON users.user_id = comments.added_by
                        INNER JOIN shop.products
                        ON products.product_id = comments.item_id
                        ");
                $connection->execute();
                $allComments = $connection->fetchAll();
                foreach($allComments as $comment){
                    echo '<tr>
                    <td>'.$comment['comment_id'].'</td>
                    <td>'.$comment['comment_text'].'</td>
                    <td> <a href="members.php?user='.$comment['username'].'">'.$comment['username'].' </a></td>
                    <td> <a href="products.php?product='.$comment['product_id'].'">'.$comment['Name'].' </a></td>
                    <td>'.$comment['added_at'].'</td>
                    <td>
                        <a commentid="'.$comment['comment_id'].'" onclick="deleteComment(this)" class="btn btn-danger"><i
                                class="fas fa-user-times"></i> Delete</a>
                    </td>
                </tr>';
                }
                ?>




                <!-- add New Category -->

            </tbody>
        </table>
    </div>
</div>

<?php include $template_directory .'footer.php' ?>


<script>
    function deleteComment(e){
        let commentId = e.getAttribute('commentid');
        console.log(commentId)
        $.post('ajax_check.php',{
            formType:'deleteComment',
            commentId:commentId
        },(data) => {
            if(data){
                location.reload()
            }
        }
        )
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