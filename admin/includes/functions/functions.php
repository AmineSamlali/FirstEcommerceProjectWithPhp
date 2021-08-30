<?php
    // This Function to get title;
    function getTitle(){
        global $pageName;
        return isset($pageName) ? $pageName : 'No Name';
    };

    // clean Field
    function clean($data){
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = trim($data);
        $data = strip_tags($data);
        return $data;
    }
    
    //clean list of Fileds
    function cleanListOfFieldes($list){
        
        foreach($list as $key => $value){
            $list[$key] = clean($value);
        };

        return $list;
    }

    
    function checkLength($field,$length){
        if (isset($field) && strlen($field) >= $length ){
            return true;
        }else{
            return false;
        }
        
    }

    function checkIssetFields($method , $list){
        for ($i = 0 ; $i < count($list) ; $i++){
            if(!isset($method[$list[$i]])){
                return false;
            }
        }
        return true;
    }
    function doAlert($status,$yes,$no){
        if($status){
            return "<script>alert('$yes')</script>";
        }else{
            return "<script>alert('$no')</script>";
        }
    }
    function outPutArray($array){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
    function checkImage($nameFiled, $target ){


        if( !($_FILES[$nameFiled][$target] === 'image/gif')
        ||!($_FILES[$nameFiled][$target] === 'image/jpeg')
        ||!($_FILES[$nameFiled][$target] === 'image/jpg')
        ||!($_FILES[$nameFiled][$target] === 'image/pjpeg')
        ||!($_FILES[$nameFiled][$target] === 'image/x-png')
        ||!($_FILES[$nameFiled][$target] === 'image/png') && !($_FILES[$nameFiled]['size'] < 200000)) {
            return false;
        }
    }
