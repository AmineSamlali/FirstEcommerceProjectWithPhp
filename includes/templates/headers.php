
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title><?php echo getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css ?>front.css" />
</head>

<body>
    <?php if (isset($_SESSION['username'])) {
        // echo checkUserStatus($_SESSION['username'],$_SESSION['password']);?>




    <div class="upper-bar">
        <div class="container">

            <img class="my-image img-thumbnail img-circle" src="img.png" alt="img.png" />
            <div class="btn-group my-info">
                <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo $_SESSION['username'] ?>
                    <span class="caret">
                    </span>
                </span>
                <ul class="dropdown-menu">
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="items.php">Add New Item</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    <?php }?>

    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
                    aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="products.php">Homepage</a>
            </div>
            <div class="collapse navbar-collapse" id="app-nav">

            
                <ul class="nav navbar-nav navbar-right">
                    
                    <?php

                $categorys = fetchAllFromTeble('id,Name,Description', 'shop.categorys', 'WHERE Visibility = 1');

                foreach ($categorys as $category) {
                    echo '<li>';
                    echo "<a href=\"products.php?categorys=" . $category['id'] . '&categoryName=' . $category['Name'] . '"/>';
                    echo '<span title="' . $category['Description'] . '">' . $category['Name'] . '</span>';
                    echo '</a>';
                    echo '</li>';
                }

                ?>


                    <?php if(!isset($_SESSION['username'])){ ?>
                            <li>
                                <a href="login.php">
                                                    <span title="Login Or Register To You'r Account Now." class="badge badge-secondary">login/register</span>
                                                    </a>
                                                </li>

                                            </ul>

                                    </ul>
                        <?php }?>

            </div>
        </div>


    </nav>