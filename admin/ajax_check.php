<?php
    session_start();
    include 'connect.php';

    include 'includes/functions/functions.php';
    include 'includes/functions/sql_functions.php';

    // global Variables ;

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // check for username input from add new user
        if($_POST['formType'] === 'delete-user'){
            if(isset($_POST['userid'])){
                
                $check = checkField('shop.users' , '*' , [
                    'filed_name' => 'user_id',
                    'value' => clean($_POST['userid'])
                ]);
                if ($check > 0){

                    addLog('Deleting User Information\'s: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['userid'] , 'getField' => 'username'] ,true  , $_SESSION['username']);

                    $connection = $conn->prepare('DELETE FROM shop.users WHERE user_id = ? ');
                    $connection->execute([$_POST['userid']]);
                    $done = $connection->rowCount();
                    echo $done;
                    
                }
            }
        }elseif($_POST['formType'] === 'getEditeForm'){
            if(isset($_POST['userId'])){
                $check = checkField('shop.users' , '*' , [
                    'filed_name' => 'user_id',
                    'value' => clean($_POST['userId'])
                ]);
                if ($check > 0){
                    addLog('Show User Information\'s: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['userId'] , 'getField' => 'username'] ,true  , $_SESSION['username']);
                    $userId = $_POST['userId'];
                    $connection = $conn->prepare('SELECT username , full_name , email FROM shop.users WHERE user_id = ?');
                    $connection->execute([$userId]);
                    $data = $connection->fetch();
                    echo json_encode($data);
    
                };

            }
        }elseif($_POST['formType'] === 'EditeForm'){
            if(checkIssetFields($_POST , ['user_id' , 'username' ,'fullName' ,'email' ,'password'])){
                $check = checkField('shop.users' , '*' , [
                    'filed_name' => 'user_id',
                    'value' => clean($_POST['user_id'])
                ]);
                if ($check > 0){
                    addLog('Editing User Information\'s: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['user_id'] , 'getField' => 'username'] ,true  , $_SESSION['username']);

                    if (strlen($_POST['password'] > 0)){
                        $connection = $conn->prepare("UPDATE shop.users SET `username` = ? , `full_name` = ? , `email` = ? , `password` = ? WHERE `user_id` = ? ");
                        $connection->execute([$_POST['username'] , $_POST['fullName'] , $_POST['email'] ,$_POST['password'], $_POST['user_id']]);
                    }else{
                        $connection = $conn->prepare("UPDATE shop.users SET username = ? , `full_name` = ? , `email` = ? WHERE `user_id` = ? ");
                        $connection->execute([$_POST['username'] , $_POST['fullName'] , $_POST['email'], $_POST['user_id']]);
                    }
                    if ($connection->rowCount() > 0){
                        echo 1;
                    }
                }
            }

        }elseif ($_POST['formType'] === 'user-activate'){

            $userId = $_POST['user_id'];
            $check = checkField('shop.users' , '*' , [
                'filed_name' => 'user_id',
                'value' => clean($userId)
            ]);
            if ($check > 0){
                addLog('Activating User: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['user_id'] , 'getField' => 'username'] ,true  , $_SESSION['username']);

                $connection = $conn -> prepare("UPDATE shop.users SET reg_status = '1' WHERE user_id = ?");
                $connection -> execute([$userId]);

                echo 1;
            }

        }elseif($_POST['formType'] === 'showUserInfo'){
            $userId = $_POST['user_id'];
            $check = checkField('shop.users' , 'username' , [
                'filed_name' => 'user_id',
                'value' => clean($userId)
            ]);
            if ($check > 0){
                $data = fetchMyColumn('username,full_name,email', 'shop.users' , "user_id = " . $_POST['user_id']);
                
                addLog('showing User Information\'s: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['user_id'] , 'getField' => 'username'] ,true  , $_SESSION['username']);

                echo json_encode($data);
            }
        }elseif($_POST['formType'] === 'addNewCategory'){
            if (checkIssetFields($_POST, ['categoryName' , 'categoryDesc' ,'flexRadioVisibility' ,'flexRadioComments' ,'flexRadioAds'])) {
                $connection = $conn->prepare("INSERT INTO shop.categorys(Name,Description,Visibility,Comments,Ads) VALUES(?,?,?,?,?) ");
                $connection->execute([$_POST['categoryName'] , $_POST['categoryDesc'] , $_POST['flexRadioVisibility'] , $_POST['flexRadioComments'] , $_POST['flexRadioAds'] ]);
                $done = $connection->rowCount();
                echo $done;
                if($done == 1){
                    addLog('Adding New Category: "' .$_POST['categoryName'].'"' ,$_SESSION['username'] );
                }
            }

        }elseif($_POST['formType'] === 'CheckFree'){
            if(isset($_POST['fieldsValue']) and isset($_POST['fieldName']) and isset($_POST['tableName'])){
                echo checkField($_POST['tableName'],'*',['filed_name' => $_POST['fieldName'] ,'value' => $_POST['fieldsValue']]);
            }
        }elseif($_POST['formType'] === 'ShowInfoCategory'){
            if(isset($_POST['cateId'])){
                $check = checkField('shop.categorys' , '*' , [
                    'filed_name' => 'id',
                    'value' => clean($_POST['cateId'])
                ]);
                if ($check > 0){
                    addLog('showing Category Information: ' , ['fieldName'=>'id' ,'table' => 'shop.categorys' , 'value' => $_POST['cateId'] , 'getField' => 'Name'] ,true  , $_SESSION['username']);
                    $cateId = $_POST['cateId'];
                    $connection = $conn->prepare('SELECT Name , Description , Visibility,Comments ,Ads FROM shop.categorys WHERE id = ?');
                    $connection->execute([$cateId]);
                    $data = $connection->fetch();
                    echo json_encode($data);
    
                };

            }
        }elseif($_POST['formType'] === 'EditeCategory'){
            if(checkIssetFields($_POST, ['cateId','categoryName' , 'categoryDesc' ,'flexRadioVisibility' ,'flexRadioComments' ,'flexRadioAds'])){
                $check = checkField('shop.categorys' , '*' , ['filed_name' => 'id','value' =>clean( $_POST['cateId'])]);
                if($check > 0){ 
                    addLog('editing Category: "' .$_POST['categoryName'].'"' ,$_SESSION['username'] );
                    $connection = $conn->prepare("UPDATE shop.categorys SET `Name` = ?, `Description` = ? , `Visibility` =?, `Comments` = ? ,`Ads`=?  WHERE `id` = ? ");
                    $connection->execute([$_POST['categoryName'] , $_POST['categoryName'] , $_POST['flexRadioVisibility'] ,$_POST['flexRadioComments'],$_POST['flexRadioAds'], $_POST['cateId']]);
                    $data = $connection->rowCount();
                    echo $data;
                }
            }
        }elseif($_POST['formType'] === 'delete-cate'){
            if(isset($_POST['cateId'])){
                $check = checkField('shop.categorys' , '*' , [
                    'filed_name' => 'id',
                    'value' => clean($_POST['cateId'])
                ]);

                if ($check > 0){
                    $e = addLog('Deleting Category: ' , ['fieldName'=>'id' ,'table' => 'shop.categorys' , 'value' => $_POST['cateId'] , 'getField' => 'Name'] ,true  , $_SESSION['username']);

                    $connection = $conn->prepare('DELETE FROM shop.categorys WHERE id = ? ');
                    $connection->execute([$_POST['cateId']]);
                    $done = $connection->rowCount();
                    echo $done;
                }
            }
        }elseif($_POST['formType'] === 'ShowInfoProduct'){
            if (isset($_POST['productId'])) {
                $check = checkField('shop.products', '*', [
                    'filed_name' => 'product_id',
                    'value' => clean($_POST['productId'])
                ]);
            }
            if ($check > 0){
                $e = addLog('Show Product Info: ' , ['fieldName'=>'product_id' ,'table' => 'shop.products' , 'value' => $_POST['productId'] , 'getField' => 'Name'] ,true  , $_SESSION['username']);
                $data = fetchMyColumn('Name,Description,price,Added_Date,Added_by,Category,tags,Country_Made,Status,Rating,Image', 'shop.products' , "product_id = " . $_POST['productId']);
                echo json_encode($data);

            }
        }elseif($_POST['formType'] === 'delete-product'){
            if (isset($_POST['productId'])) {
                $check = checkField('shop.products', '*', [
                    'filed_name' => 'product_id',
                    'value' => clean($_POST['productId'])
                ]);
                if($check >  0){
                    $e = addLog('Deleting Product: ' , ['fieldName'=>'product_id' ,'table' => 'shop.products' , 'value' => $_POST['productId'] , 'getField' => 'Name'] ,true  , $_SESSION['username']);
                    $image = fetchMyColumn('Image','shop.products','product_id="'.$_POST['productId'].'"','data','One');
                    $directory = dirname(__DIR__).'\data\uploads\\' . $image['Image'];
                    unlink($directory);
                    $connection = $conn->prepare("DELETE FROM shop.products WHERE product_id = ?");
                    $connection->execute([$_POST['productId']]);
                    $res = $connection->rowCount();
                    echo $res;

                }
            }

        }elseif($_POST['formType'] === 'addNewProduct'){
            if(checkIssetFields($_POST, ['name','description' , 'price' ,'addedDate' ,'addedBy' ,'category' , 'tags','madeOn','rating'])){
                if(!isset($_FILES['img'])){
                    echo 0;
                    exit();
                }
                    
                
                $location = dirname(__DIR__) . "\data\uploads\\";
                $files = scandir($location, SCANDIR_SORT_DESCENDING);
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

                addLog('Add New Product: "' .$_POST['name'].'"' ,$_SESSION['username'] );
                $connection = $conn->prepare("INSERT INTO shop.products(Name,Description,Price,Added_Date,Country_Made,status,Category,Added_by,tags,Rating,Image) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                $connection->execute(
                    [$_POST['name'],
                    $_POST['description'],
                    $_POST['price'],
                    $_POST['addedDate'],
                    $_POST['madeOn'],
                    $_POST['status'],
                    $_POST['category'],
                    $_POST['addedBy'],
                    $_POST['tags'],
                    $_POST['rating'],
                    $product_image,
                ]);
                $status = $connection->rowCount();
                echo $status;
            }

        }elseif($_POST['formType'] === 'EditeProduct'){
            if (checkIssetFields($_POST, ['product_Id','name','description' , 'price' ,'addedDate' ,'addedBy' ,'category' , 'tags','madeOn' , 'status','rating'])) {
                $check = checkField('shop.products' , '*' , ['filed_name' => 'product_id','value' => clean($_POST['product_Id'])]);
                
                if($check > 0){
                    addLog('editing Category: "' .$_POST['name'].'"' ,$_SESSION['username'] );
                    $method = cleanListOfFieldes($_POST);
                    if(isset($_FILES['img'])){
                        // get Current Image
                        $currentImage = fetchMyColumn('Image','shop.products','product_id = '. $_POST['product_Id'],'data','One')['Image'];
                        // get Name of The Current Image and store it
                        $location = dirname(__DIR__) . "\data\uploads\\" . $currentImage; 
                        // unlick it
                        unlink($location);
                        // move new image and rename it put(oldImageName);
                        move_uploaded_file($_FILES['img']['tmp_name'],dirname(__DIR__) . "\data\uploads\\" . basename($currentImage));
                        // sql
                        $image = ", `Image` = ?";
                    }else{
                        $image = '';
                    };
                    $connection = $conn->prepare("UPDATE shop.products SET `Name` = ?, `Description` = ? , `Price` =?, `Added_Date` = ? ,`Country_Made`= ?,`Added_by`=?,`tags`=? ,`Status` = ? ,`Rating` = ? $image WHERE `product_id` = ? ");
                    if(!empty($image)){
                        $container = [$method['name'] , $method['description'] , $method['price'] ,$method['addedDate'],$method['madeOn'], $method['addedBy'], $method['tags'],$method['status'],$method['rating'],$currentImage,$method['product_Id']];
                    }else{
                        $container = [$method['name'] , $method['description'] , $method['price'] ,$method['addedDate'],$method['madeOn'], $method['addedBy'], $method['tags'],$method['status'],$method['rating'],$method['product_Id']];

                    }
                    $connection->execute($container);
                    $data = $connection->rowCount();
                    if(!empty($image) and $data == 0){
                        echo 1;
                    }else{
                        echo $data;
                    }
                    
                }
            }
        }elseif($_POST['formType'] === 'checkFor-user&categorys'){
            if(sqlCount('username','shop.users') and sqlCount('Name','shop.categorys')){
                echo '1';
            }else{
                echo '0';
            }
        }elseif($_POST['formType'] === 'product-activate'){
            $productId = $_POST['product_id'];
            $check = checkField('shop.products' , 'Name' , [
                'filed_name' => 'product_id',
                'value' => clean($productId)
            ]);
            if ($check > 0){
                addLog('Activating Product: ' , ['fieldName'=>'product_id' ,'table' => 'shop.products' , 'value' => $_POST['product_id'] , 'getField' => 'Name'] ,true  , $_SESSION['username']);
                $connection = $conn -> prepare("UPDATE shop.products SET Status = '1' WHERE product_id = ?");
                $connection -> execute([$productId]);
                echo 1;
            }

        }elseif($_POST['formType'] === 'addNewComment'){
            $connection = $conn->prepare("INSERT INTO shop.comments(comment_text,added_by,item_id) VALUES(? ,? ,?) ");
            $connection->execute([
                $_POST['commentValue'],
                $_SESSION['user_id'],
                $_POST['currentProduct'
                ]
            ]);
            $res = $connection->rowCount();
            echo $res;
        }elseif($_POST['formType'] == 'DeleteComment'){
            // check for id an the other
            if (isset($_POST['commentId'])) {
                $check = checkField('shop.comments', '*', [
                    'filed_name' => 'comment_id',
                    'value' => clean($_POST['commentId'])
                ],'available','AND added_by="'.$_SESSION['user_id'].'"');
                if($check >  0){
                    $connection = $conn->prepare("DELETE FROM shop.comments WHERE comment_id = ?");
                    $connection->execute([$_POST['commentId']]);
                    $res = $connection->rowCount();
                    echo $res;
                }
            }
        }elseif($_POST['formType'] === 'user-ban'){
            $userId = $_POST['user_id'];
            $check = checkField('shop.users' , '*' , [
                'filed_name' => 'user_id',
                'value' => clean($userId)
            ]);
            if ($check > 0){
                addLog('Ban User: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['user_id'] , 'getField' => 'username'] ,true  , $_SESSION['username']);
                $connection = $conn -> prepare("UPDATE shop.users SET trust_status = 0 WHERE user_id = ?");
                $connection -> execute([$userId]);
                echo 1;
            }

        }elseif($_POST['formType'] === 'user-unBan'){
            $userId = $_POST['user_id'];
            $check = checkField('shop.users' , '*' , [
                'filed_name' => 'user_id',
                'value' => clean($userId)
            ]);
            if ($check > 0){
                addLog('Ban User: ' , ['fieldName'=>'user_id' ,'table' => 'shop.users' , 'value' => $_POST['user_id'] , 'getField' => 'username'] ,true  , $_SESSION['username']);
                $connection = $conn -> prepare("UPDATE shop.users SET trust_status = 1 WHERE user_id = ?");
                $connection -> execute([$userId]);
                echo 1;
            }
        }elseif($_POST['formType'] === 'showImageImage'){

            $target_dir = 'C:\xampp\htdocs\server\shop\\addProductCash\\';

            $resource = explode('.',preg_replace('/\./','', $_FILES['fileName']['name'],substr_count($_FILES['fileName']['name'],'.') - 1))[1];

            $imageName = strval(random_int(10000,213144)) .'.'. $resource;
            if(isset($_FILES['fileName'])){
                if(move_uploaded_file($_FILES['fileName']['tmp_name'],$target_dir.$imageName)){
                    if(!empty($_SESSION['cash'])){
                        unlink( $target_dir . $_SESSION['cash']);
                    }
                    
                    $_SESSION['cash'] = $imageName;

                    echo 'addProductCash/' . $imageName;
                    
                }else{
                    echo 0;
                }
    
            }
            
        }elseif($_POST['formType'] == 'setting-update'){

            if( isset($_POST['email']) and isset($_POST['maintenanceMode']) and $_POST['maintenanceMode'] == 1 or $_POST['maintenanceMode'] == 0 ){
                $connection = $conn->prepare("UPDATE shop.settings SET maintenance_mode = ?, email = ? WHERE id = ?");
                $connection->execute([$_POST['maintenanceMode'],$_POST['email'] , 1]);
            }
        }elseif($_POST['formType'] == 'delete-log'){
            $connection = $conn->prepare("DELETE FROM shop.log");
            $connection->execute();
            
        }
    }