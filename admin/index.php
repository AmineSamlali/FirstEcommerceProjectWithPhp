
<!--[START] PHP loginForm -->

<?php 
    session_start();
    session_regenerate_id();


    // [START] Variables
    $noNavBar = '';
    $pageName = 'Login Page';
    // [END] Variables
    
    include 'init.php';
    include $functions_directory . 'sql_functions.php';
    
    if (isset($_SESSION['username'])) {
        header('location:dashboard.php');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (isset($_POST['ip'])){
            echo $_POST['ip'];
        }


        if (strlen($_POST['username']) > 0 && strlen($_POST['password']) > 0){
            
            $hashedPwd = sha1($password);
            $sql = $conn->prepare('SELECT user_id ,username, password , full_name , email ,registration_date,Image,group_id FROM users WHERE username = ? AND password = ? AND group_id = 1');
            $sql->execute([$username , $hashedPwd]);
            $data = $sql->fetch();
            $auth = $sql->rowCount();
            
            if ($auth > 0){
                updateIp($data['user_id'] , $_POST['user_ip']);
                $_SESSION['user_id'] = $data['user_id'];
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['full_name'] = $data['full_name'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['reg_date'] = $data['registration_date'];
                $_SESSION['picture'] = $data['Image'];
                $_SESSION['group_id'] = $data['group_id'];

                header('location:dashboard.php');
                exit();
                
            };
        };
    };



?>

<!--[END] PHP loginForm -->


<form method="POST" class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>">

    <h2 class="text-center">Admin Login</h2>
    <input type="text" class="form-control input-lg" name="username" id="username" placeholder="username"
        autocomplete="off" required>
    <input type="password" name="password" class="form-control input-lg" id="password" placeholder="password"
        autocomplete="new-password" required>
    <input type="hidden" name="user_ip" id="user_ip">
    <input type="submit" class="btn btn-primary btn-block" value="Login">

</form>
<script>
    // fix if user Delete That Field
    $.getJSON("https://api.ipify.org?format=json",function(data) {document.getElementById('user_ip').value = data.ip;})
    
</script>

<?php include $template_directory . 'footer.php'; ?>