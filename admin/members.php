<?php 
    session_start();
    include 'check_auth.php';

    $pageName = 'All Members';
    include 'urls.php';
    include 'connect.php';
    include 'init.php';
    include $functions_directory . 'sql_functions.php';
?>
<?php 

        if ($_SERVER["REQUEST_METHOD"] === 'POST'){
                $username = $_POST['username'];
                $fullName = $_POST['fullName'];
                $email = $_POST['email'];
                $password1 = $_POST['password1'];
                $password2 = $_POST['password2'];
                if (checkLength($username , 8) && checkLength($password1,8) && checkLength($password2,8) && isset($fullName) && isset($email)){
    
                    if(strlen(clean($password1)) === strlen(clean($password2))){
                        
                        if (checkField('shop.users','*',['filed_name'=>'username','value'=>clean($username)]) === 0){
                            
                            $sql = $conn->prepare('INSERT INTO shop.users(username,password,full_name,email,reg_status ,user_log) VALUES(:username,:password,:full_name,:email,:reg_status,:user_log) ');
                            $user_log = [
                                'CreatedBy' => $_SESSION['username'],
                                'CreatedDate' => date("Y-m-d H:i:s")
                            ];
                            $sql->execute([
                                'username' => clean($username),
                                'password' => sha1(clean($password1)),
                                'full_name' => clean($fullName),
                                'email' => clean($email),
                                'reg_status' => 1,
                                'user_log' => json_encode($user_log)
                            ]);

                        echo ($sql->rowCount() == 1 ) ? '<script>alert("user added successfully")</script>' : '<script>alert("Invalid Information\'s")</script>';
                        if($sql->rowCount() == 1){
                            addLog('Adding New User: "' .$_POST['username'].'"' ,$_SESSION['username'] );
                        }

                    }else{
                        echo '<script>alert("Invalid Information\'s")</script>';
                    }
    
                    }else{
                        echo '<script>alert("Enter The Same Password")</script>';

                    }
                    }
            }
        

    ?>
<div id="body">

    <h1 class="text-center">All Memebers</h1>
    <div id="userTable">
        <div class="container">

            <div class="table-responsive">
                <a href="#" class="btn btn-primary" style="float:right;margin-bottom: 3e20;margin-bottom: 5px;"
                    data-toggle="modal" data-target="#myModal">Add New
                    Member</a>

                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Full Name</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>verified</td>
                        <td>User Status</td>
                        <td>Registred Date</td>
                        <td>Options</td>
                    </tr>

                    <?php
        
            $connect = $conn->prepare('SELECT * FROM shop.users WHERE user_id != 1');
            $connect->execute();

            $data = $connect->fetchAll();

            foreach($data as $user){
                $userSatus = $user['group_id'] == 0 ? 'Running' : 'Banned';
                $trustedStatus = $user['reg_status'] == 1 ? 'Yes' : 'No';
                echo '<tr>';
                echo '<td>'.$user['user_id'].'</td>';
                        echo '<td>'.$user['full_name'].'</td>';
                        echo '<td>'.$user['username'].'</td>';
                        echo '<td>'.$user['email'].'</td>';
                        echo '<td>'.$trustedStatus .'</td>';
                        echo '<td>'.$userSatus .'</td>';
                        echo '<td>'.$user['registration_date'].'</td>';
                        echo '<td>
                            <a  data-toggle="modal" userid = '.$user['user_id'].' onclick = "editeMember(this)"  class="btn btn-success"><i class="fas fa-user-edit"></i> Edite</a>
                            <a userid = '.$user['user_id'].' onclick = "deleteUser(this)" class="btn btn-warning"><i class="fas fa-user-times"></i> Delete</a>';
                            if($user['trust_status'] == 0){
                                echo '<a userid = '.$user['user_id'].'  href="#" onclick=unBanUser(this) class="btn btn-danger"><i class="fas fa-user-slash"></i> unBan</a>';
                            }else{
                                echo '<a userid = '.$user['user_id'].' href="#" onclick=banUser(this) class="btn btn-danger"><i class="fas fa-user-slash"></i> Ban</a>';

                            }
                            if ($user['reg_status'] == 0){
                                echo '<a userid='.$user['user_id'].' style="margin-top: 3px;" onclick="activeUser(this)" class="btn btn-info"><i class="fas fa-user-check"></i> Activate</a>';

                            }
                        echo '</td>';
                        echo '</td>';
            };
        ?>


                    <div class="modal fade" id="myModal1">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" id="editeBtnClose"
                                        data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Edite Member : <span id="editeUserName"></span></h4>

                                </div>
                                <div class="modal-body">
                                    <form method="POST" name="editeFormUserName" autocomplete="off" id="editeFormUser">
                                        <input type="hidden" name="formType" value="EditeInitUser">
                                        <div class="form-group">
                                            <label for="exampleInputUserName2">User Name</label>
                                            <input name="username" type="text" class="form-control" minlength="8"
                                                id="exampleInputUserName2" placeholder="enter a username"
                                                autocomplete="off" required disabled>
                                            <!-- <small id="usernameStatus"  class="form-text text-muted" hidden>this username its already taken.</small> -->
                                            <small id="editeUsernameStatus" class="form-text text-muted">Please do not
                                                change your username if absolutely necessary,<a
                                                    onclick="changeUserName()" style="color:red;cursor: pointer;"> click
                                                    here</a> For Change it.</small>

                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFullName2">Full Name</label>
                                            <input name="fullName" type="text" class="form-control"
                                                id="exampleInputFullName2" placeholder="Full Name" autocomplete="off"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail2">Email Address</label>
                                            <input name="email" type="email" class="form-control"
                                                id="exampleInputEmail2" aria-describedby="emailHelp" autocomplete="off"
                                                placeholder="Enter email" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassWord2">Change Password</label>
                                            <input name="editePassword1" type="password" class="form-control"
                                                id="editeExampleInputPassWord1"
                                                placeholder="Leave it blank if you don't wanna change the password"
                                                autocomplete="off" minlength='8'>
                                        </div>

                                        <br>
                                        <center><input type="submit" id="btnEditeUser" class="btn btn-primary"
                                                value="Edite Member">
                                        </center>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add New Member</h4>
                                </div>
                                <div class="modal-body">


                                    <form class="form-horizontal" method="POST"
                                        action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                        <input type="hidden" name="formType" value="AddNewMember">

                                        <div class="form-group">
                                            <label for="exampleInputUserName1">User Name</label>
                                            <input name="username" type="text" class="form-control" minlength="8"
                                                id="exampleInputUserName1" placeholder="enter a username"
                                                autocomplete="off" required>
                                            <small id="usernameStatus" class="form-text text-muted" hidden>this username
                                                its already taken.</small>

                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFullName1">Full Name</label>
                                            <input name="fullName" type="text" class="form-control"
                                                id="exampleInputFullName1" placeholder="Full Name" autocomplete="off"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email Address</label>
                                            <input name="email" type="email" class="form-control"
                                                id="exampleInputEmail1" aria-describedby="emailHelp" autocomplete="off"
                                                placeholder="Enter email" required>
                                            <small id="emailHelp" class="form-text text-muted">We'll never share your
                                                email with
                                                anyone else.</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassWord1">Pass Word</label>
                                            <input name="password1" type="password" class="form-control"
                                                id="exampleInputPassWord1" placeholder="Enter a password"
                                                autocomplete="off" minlength='8' required>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputPassWord2">Re Pass Word</label>
                                            <input name="password2" type="password" class="form-control"
                                                id="exampleInputPassWord2" placeholder="Enter a password"
                                                autocomplete="off" minlength='8' required>
                                            <small id="passwordStatus" class="form-text text-muted" hidden>Please Enter
                                                The Same Password.</small>
                                        </div>
                                        <center><button type="submit" class="center btn btn-primary" id='btnAdd'>Add New
                                                User</button></center>


                                    </form>


                                    </form>

                                </div>
                            </div>

                        </div>

                    </div>
                </table>
            </div>
        </div>

    </div>


</div>


<?php include $template_directory .'footer.php' ?>

<script>

// username Check On add user Form
checkFileds('#exampleInputUserName1' , 8,'ajax_check.php' , 'btnAdd' , 'usernameStatus',{fieldName:'username',tableName:'shop.users'});


</script>

<script>
function deleteUser(event) {
    let confirmed = confirm('Are You sure ?');
    if (confirmed) {
        let userId = event.getAttribute('userid')
        $.post('ajax_check.php', {
            formType: 'delete-user',
            userid: userId
        }, (data) => {
            location.reload();
        })

    }
}
// VARS Edite Memebers
var user_id = '';

function editeMember(ev) {

    let firstEvent = ev;
    let userId = firstEvent.getAttribute('userid');
    user_id = userId;
    firstEvent.setAttribute('data-target', '#myModal1');

    //[START] Set Values To Fields;
    $.post('ajax_check.php', {
        formType: 'getEditeForm',
        userId: userId
    }, (data) => {
        let editeUsername = document.getElementById('exampleInputUserName2');
        let editeFullName = document.getElementById('exampleInputFullName2');
        let editeEmail = document.getElementById('exampleInputEmail2');

        let ajaxData = JSON.parse(data);
        document.getElementById('editeUserName').innerHTML = ajaxData['username'];

        editeUsername.value = ajaxData['username'];
        editeFullName.value = ajaxData['full_name'];
        editeEmail.value = ajaxData['email'];



    });

    // [END] Set Values;


}


$('#editeFormUser').submit(function(e) {
    e.preventDefault();
    let editeUsername = document.getElementById('exampleInputUserName2');
    let editeFullName = document.getElementById('exampleInputFullName2');
    let editeEmail = document.getElementById('exampleInputEmail2');

    if (editeUsername.value.length >= 8 && editeFullName.value.length > 0 && editeEmail.value.length > 0) {

        $.post('ajax_check.php', {
            formType: 'EditeForm',
            user_id: user_id,
            username: editeUsername.value,
            fullName: editeFullName.value,
            email: editeEmail.value,
            password: document.getElementById('editeExampleInputPassWord1').value
        }, (data) => {
            if (data == 1) {
                location.reload();
            }
        });

    }

})

function activeUser(e) {
    let userId = e.getAttribute('userid');
    $.post('ajax_check.php', {
        formType: 'user-activate',
        user_id: userId
    }, (data) => {
        if (data == 1) {
            location.reload();
        }
    })
}


function changeUserName() {
    document.getElementById('exampleInputUserName2').toggleAttribute('disabled')
}

function banUser(event){
    let userId = event.getAttribute('userid');
    $.post('ajax_check.php', {
        formType: 'user-ban',
        user_id: userId
    }, (data) => {
        console.log(data);
        if (data == 1) {
            location.reload();
        }
    })

};


function banUser(event){
    let userId = event.getAttribute('userid');
    $.post('ajax_check.php', {
        formType: 'user-ban',
        user_id: userId
    }, (data) => {
        console.log(data);
        if (data == 1) {
            location.reload();
        }
    })

};

function unBanUser(event){
    let userId = event.getAttribute('userid');
    $.post('ajax_check.php', {
        formType: 'user-unBan',
        user_id: userId
    }, (data) => {
        console.log(data);
        if (data == 1) {
            location.reload();
        }
    })

};

</script>