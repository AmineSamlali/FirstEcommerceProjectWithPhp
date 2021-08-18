<?php 
	session_start();

if (isset($_SESSION['username'])){

	header('location:profile.php');
	exit();

}

$pageName  = 'Login/Register';


include 'init.php';

?>

<?php 
	$errorMsg = '';

	if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if (isset($_POST['login'])) {
            $username = clean($_POST['username']);
            $password = clean($_POST['password']);
            if (strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0) {
                    $connection = $conn->prepare("SELECT user_id ,username, password , full_name ,registration_date ,  email FROM shop.users WHERE username = ? AND password = ? AND trust_status = 1");
                    $connection->execute([$username , sha1($password)]);
                    if($connection->rowCount()){
                        $data = $connection->fetch();
                        if ($connection->rowCount()) {
                            if (isset($_POST['user_ip'])) {
                                updateIp($_POST['username'], $_POST['user_ip']);
                            };
                            $_SESSION['user_id'] = $data['user_id'];
                            $_SESSION['username'] = $username;
                            $_SESSION['password'] = $password;
                            $_SESSION['full_name'] = $data['full_name'];
                            $_SESSION['email'] = $data['email'];
                            $_SESSION['reg_date'] = $data['registration_date'];
                            $_SERVER['pwd'] = $data['password'];
                            header('location:profile.php');
                        }
                    }else{
                        $errorMsg = (checkUserStatus($username,sha1($password)) == 1) ? 'Username Or Password Invalid!' : 'Your Account Has Been Banned !';
                    }
            }else{
            
                $errorMsg = 'Please try again! e';

            }
        }elseif(isset($_POST['signup'])){
            $username 	= clean($_POST['username']);
			$password 	= clean($_POST['password']);
			$password2 	= clean($_POST['password2']);
			$email 		= clean($_POST['email']);
            $fullName = clean($_POST['full_name']);
            if(checkIssetFields($_POST , ['username','password','password2','email','full_name'])){
                if (strlen($username) >= 8 and strlen($password) >= 8 and strlen($password2) >= 8) {
                    if (checkField('shop.users', 'username', ['filed_name'=>'username','value'=>clean($username)]) === 0) {
                        if ($password === $password2) {
                            $connection = $conn->prepare('INSERT INTO shop.users(username,password,full_name,email) VALUES(?,?,?,?)');
                            $connection->execute([$username , sha1($password) , $fullName , $email]);
                            $errorMsg = 'Congrats You\'r Account has Been Created';
                        }
                    }else{
                        $errorMsg = 'Please Enter True Information\'s';
                    }
                }

            }

        }else{
            $errorMsg = 'Please Try again !';
        }
	}

?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> |
        <span data-class="signup">Signup</span>
    </h1>
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type your username"
                required />
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password"
                placeholder="Type your password" required />
        </div>
        <input type="hidden" name="user_ip" id="user_ip">

        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
    </form>
    <!-- End Signup Form -->
    <div class="the-errors text-center">

        <?php 
			if (strlen($errorMsg) > 0){
				echo "<div class = \"msg error\">$errorMsg</div>";
			};
			?>


    </div>

    <!-- End Login Form -->
    <!-- Start Signup Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

        <div class="input-container">
            <input title="Enter You'r First and Last Name" class="form-control" type="text" name="full_name"
                autocomplete="off" placeholder="Type your Full Name" required />

        </div>

        <div class="input-container">
            <input id="userCheck" pattern=".{8,}" title="Username Must Be Between  Chars" class="form-control"
                type="text" name="username" autocomplete="off" placeholder="Type your username" required />
            <small id="usernameStatus" class="form-text text-muted" hidden>this username
                its already taken.</small>

        </div>

        <div class="input-container">
            <input minlength="8" class="form-control" type="password" name="password" autocomplete="new-password"
                placeholder="Type a Complex password" required />
        </div>
        <div class="input-container">
            <input minlength="8" class="form-control" type="password" name="password2" autocomplete="new-password"
                placeholder="Type a password again" required />
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" placeholder="Type a Valid email" required />
        </div>
        <input class="btn btn-success btn-block" name="signup" id="btnAdd" type="submit" value="Signup" />
    </form>
    <!-- End Signup Form -->



</div>



<?php include $tpl . 'footer.php'; ?>


<script>
checkFileds('#userCheck', 8, 'admin/ajax_check.php', 'btnAdd', 'usernameStatus', {
    fieldName: 'username',
    tableName: 'shop.users'
});


$.getJSON("https://api.ipify.org?format=json", function(data) {
    document.getElementById('user_ip').value = data.ip;
})
</script>