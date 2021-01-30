<?php
require_once "../PhpClasses/Operations.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['customer_name']) and isset($_POST['customer_email'])
        and isset($_POST['customer_phone'])){
        $db = new Operations();
        $db->uploadCart($_POST['customer_name'],$_POST['customer_email'],
            $_POST['customer_phone']);
    }
}
