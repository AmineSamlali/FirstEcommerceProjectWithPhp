<?php 
    
    function translate($phrese){

        static $langsArray = array (

            // NavBar Links
            
            'HOME' => 'Home',
            'CATEGORYS' => 'Categorys',
            'ITEMS' => 'Items',
            'MEMBERS' => 'Members',
            'STATISTICS' => 'Statistics',
            'LOGS' => 'Logs',
            "COMMENT" => "comments"

        );
        return $langsArray[$phrese];
        
    };
    