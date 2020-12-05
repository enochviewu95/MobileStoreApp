<?php

require_once dirname(__FILE__) . '/Connect.php';
class Operations
{
    private $con;

    function __construct()
    {
        $db = new Connect();
        $this->con = $db->connect();
    }

    public function registerUser($name,$email,$phone,$password){
        if($this->isUserExit($phone)){
            return 0;
        }else{
            $ePassword = md5($password);
            $query = "insert into users
                            (name,email,password,contact)
                            values(?,?,?,?);";

            $stmt = $this->con->prepare($query);
            $stmt->bind_param("sssi",$name,$email,
                $ePassword,$phone);

            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }
    }

    private function isUserExit($phone)
    {
        $query = "select id from users where contact = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("i",$phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}