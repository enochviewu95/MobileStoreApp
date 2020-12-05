<?php
require_once '../PhpScripts/Constants.php';

class Connect
{
    //Declaration of connection variable
    private $con;

    //Declaration of constructor
    function __construct(){}

    //Function for connection
    function connect(){
        //Creating a mysqli object
        $this->con = new mysqli(db_host,db_user,
            db_password,db_name);

        if(mysqli_connect_errno()){
            echo 'Failed to connect with database'.
                mysqli_connect_error();
        }
        return $this->con;
    }
}