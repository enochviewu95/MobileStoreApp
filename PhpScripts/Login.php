<?php
require_once "../PhpClasses/Operations.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['email']) and isset($_POST['password'])){
        $db = new Operations();
        $result = $db->login($_POST['email'], $_POST['password']);

        if($result != null){
            $response['error'] = false;
            $response['message'] = "Login Successful";
            $response['content'] = $result;
        }else{
            $response['error'] = true;
            $response['message'] = "Login failed";
            $response['content'] = $result;
        }
    }
}

echo json_encode($response);