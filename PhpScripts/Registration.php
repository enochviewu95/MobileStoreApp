<?php

require_once "../PhpClasses/Operations.php";

$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['full_name']) and isset($_POST['email'])
        and isset($_POST['phone_number']) and isset($_POST['password'])){
        $db = new Operations();
        $result = $db->registerUser(
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone_number'],
            $_POST['password']
        );

        if($result == 1){
            $response['error'] = false;
            $response['message'] = "Registration Successful";
        }elseif($result == 2){
            $response['error'] = true;
            $response['message'] = "Registration failed";
        }elseif($result == 0){
            $response['error'] = true;
            $response['message'] = "Already registered";
        }
    }

}

echo json_encode($response);