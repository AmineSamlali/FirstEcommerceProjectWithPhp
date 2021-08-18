<?php 
    function getTitle(){
        global $pageName;
        return isset($pageName) ? $pageName : 'No Name';
    };
    