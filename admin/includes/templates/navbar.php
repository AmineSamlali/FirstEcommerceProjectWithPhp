<?php include 'urls.php'; ?>

<nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $dashboard_url; ?>"><?php echo translate('HOME')?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
        <li class="active">
          <li><a href="<?php echo $categorys_url; ?>"><?php echo translate('CATEGORYS')?></a> </li>
          <li><a href="<?php echo $products_url; ?>"><?php echo translate('ITEMS')?></a> </li>
          <li><a href="<?php echo $memebers_url; ?>"><?php echo translate('MEMBERS')?></a> </li>
          <li><a href="<?php echo $log_url; ?>"><?php echo translate('LOGS')?></a> </li>
        </li>

      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php print_r($_SESSION['username']) ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
          <li><a href="../products.php">visite Web Site</a></li>
            <li><a href="<?php echo $profile_url; ?>">Edit Profile</a></li>
            <li><a href="<?php echo $setting_url; ?>">Settings</a></li>
            <li><a href="<?php echo $logout_url; ?>">Logout</a></li>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

