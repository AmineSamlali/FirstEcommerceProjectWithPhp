<?php
    include 'admin/connect.php';

	include 'admin/includes/functions/functions.php';
	include 'admin/includes/functions/sql_functions.php';

// directorys
	$tpl 	= 'includes/templates/'; // Template Directory
	$lang 	= 'includes/languages/'; // Language Directory
	$func	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory


    // include $func.'functions.php';
	if(!isset($noNavBar)){
		include $tpl.'headers.php';

	}

	// Routes 
	$login = 'index.php';

