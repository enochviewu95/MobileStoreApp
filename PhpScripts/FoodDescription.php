<?php
require_once "../PhpClasses/Operations.php";


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['food_category_id'])){
        $db = new Operations();
        $result = $db->getFoodDescription($_POST['food_category_id']);

        if($result != null){
            $response['error'] = false;
            $response['content'] = $result;
        }else{
            $response['error'] = true;
            $response['content'] = $result;
        }
    }
}
echo json_encode($response);

