<?php

    include 'connect.php';

    // [STRAT] Routes
    $template_directory = 'includes/templates/';
    $css_directory = 'layout/css/';
    $js_directory = 'layout/js/';
    $langs_directory = 'includes/languages/';
    $functions_directory = 'includes/functions/';   
    // [END] Routes
    include $functions_directory . 'functions.php';
    include $langs_directory . 'english.php';
    include $template_directory . 'header.php';

    if (!isset($noNavBar)){

        include $template_directory . 'navbar.php';

    };

    