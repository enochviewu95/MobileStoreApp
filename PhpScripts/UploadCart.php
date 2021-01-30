<?php
require_once "../PhpClasses/Operations.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['customer_name']) and isset($_POST['customer_email'])
        and isset($_POST['customer_phone']) and isset($_POST['food_name'])
            and isset($_POST['food_option'])and isset($_POST['food_quantity']) and isset($_POST['food_price'])){
        $db = new Operations();
        $response = $db->uploadCart($_POST['customer_name'],$_POST['customer_email'],
            $_POST['customer_phone'],$_POST['food_name'],$_POST['food_option'],
            $_POST['food_quantity'],$_POST['food_price']);
    }

    echo($response);
}
