<?php 
    session_start();
    include 'check_auth.php';

    $pageName = 'Profile';
    include 'connect.php';
    include 'init.php';
?>

<?php 

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        if ($_POST['form-type'] === 'info'){

            $newfullName = $_POST['fullName'];
            $newEmail = $_POST['email'];
            if (isset($newfullName) && isset($newEmail) && strlen($newfullName) >= 8 && strlen($newEmail)){
                $update = $conn -> prepare('UPDATE shop.users SET full_name = ?, email = ? WHERE user_id = ? ');
                $update->execute([$newfullName , $newEmail , $_SESSION['user_id']]);
                $_SESSION['full_name'] = $newfullName;
                $status = $update->rowCount();
                $infoMessage = [
                    'message' => 'The Information\'s Has been Updated',
                    'class' => 'success'
                ];
            }else {
                $infoMessage = [
                    'message' => 'Please Enter true Fileds',
                    'class' => 'danger'
                ];
            }

        }elseif ($_POST['form-type'] === 'pwd'){
            
            if (isset($_POST['password1']) && isset($_POST['password2']) && strlen($_POST['password1']) >= 8 && strlen($_POST['password2'])){

                $updatePwd = $conn->prepare('UPDATE shop.users SET `password` = ? WHERE `user_id` = ? AND password = ? ');
                $updatePwd->execute([sha1($_POST['password2']) , $_SESSION['user_id'],sha1($_POST['password1'])]);
                $status = $updatePwd->rowCount();
                $message = ['message'=> ($status === 1) ? 'The Password Has Been Changed':'Please Enter True Password','class' => ($status === 1) ? 'success':'danger' ];
            } else {
                // $message = ['messge' => '']
            }

        }//123456789123456789123456789
    }

?>

            <div class="container">
                    <h1 class="text-center">Edite Profile</h1>
                    <form class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
                    <input type="hidden" name ='form-type' value="info" >
                    <div id="update_form">
                        <div class="form-group">
                            <label for="exampleInputFullName1">Full Name</label>
                            <input name="fullName" type="text" value="<?php print_r($_SESSION['full_name']); ?>" class="form-control" id="exampleInputFullName1" placeholder="Full Name" autocomplete="off" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input name="email" type="email" value="<?php print_r($_SESSION['email']); ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" autocomplete="off" placeholder="Enter email" required>
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <?php

                            if (isset($infoMessage)){
                                echo '<div class="alert alert-'.$infoMessage['class'].'" role="alert">'.$infoMessage['message'].'</div>';
                            };

                            ?>

                        <button type="submit"class="btn btn-primary"  id ='btnBtn' disabled>Update Informations</button>
            
                                        <br>
                                                <br>
                    </div>

                    </form>
                    <form id = 'login_form' class="form-horizontal" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
                    <input type="hidden" name ='form-type' value="pwd" >

                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input name="password1" type="password" class="form-control" id="exampleInputPassword1" minlength="8" placeholder="Password" autocomplete="off" required>

                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">New Password</label>
                            <input name="password2" type="password" class="form-control" id="exampleInputPassword2" minlength="8" placeholder="New Password" autocomplete="off" required>
                        
                        </div>
                        <?php

                            if (isset($message)){
                                echo '<div class="alert alert-'.$message['class'].'" role="alert">'.$message['message'].'</div>';
                            };
                        
                        ?>

                    <button type="submit" class="btn btn-primary" id="btnBtn1">Change Password</button>

                    </form>
            </div>

<script>

    let fullName = document.getElementById('exampleInputFullName1').value;
    let Email = document.getElementById('exampleInputEmail1').value;
    
    document.getElementById('exampleInputFullName1').addEventListener('input' , (e) =>{
        if (e.target.value === fullName && document.getElementById('exampleInputEmail1').value === Email){
            if (!document.getElementById('btnBtn').hasAttribute('disabled')){
                document.getElementById('btnBtn').setAttribute('disabled' ,'');
            }

            }else {
                if (document.getElementById('btnBtn').hasAttribute('disabled')){
                document.getElementById('btnBtn').removeAttribute('disabled');
            }
            }

        
    });

    document.getElementById('exampleInputEmail1').addEventListener('input' ,(e)=>{
        if (e.target.value === Email && document.getElementById('exampleInputFullName1').value === fullName){
            if (!document.getElementById('btnBtn').hasAttribute('disabled')){
                document.getElementById('btnBtn').setAttribute('disabled' ,'');
            }
        }else{
            if (document.getElementById('btnBtn').hasAttribute('disabled')){
                document.getElementById('btnBtn').removeAttribute('disabled');
            }
        }
    })
    
    


</script>






<?php include $template_directory .'footer.php' ?>;