<?php
require_once "../PhpClasses/Operations.php";

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $db = new Operations();
    $result = $db->getFoodDescription();

    if($result != null){
        $response['error'] = false;
        $response['content'] = $result;
    }else{
        $response['error'] = true;
        $response['content'] = $result;
    }

}

echo json_encode($response);