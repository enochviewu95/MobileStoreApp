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
        if($this->isUserExit($email)){
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

    public function login($email,$password){

        $ePassword = md5($password);
        $query = "select id, name, email, contact from users where email=? and password=?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("ss",$email,$ePassword);
        if($stmt->execute()){
            $stmt->bind_result($id,$dFullname,$dEmail,$dContact);
            $temp=array();
            while($stmt->fetch()){

                $temp['id'] = $id;
                $temp['full_name'] = $dFullname;
                $temp['email'] = $dEmail;
                $temp['contact']=$dContact;
            }


            return $temp;
        }

        return null;
    }

    public function getFoodDescription(){
        $query = "select * from items; ";
        $stmt = $this->con->prepare($query);
        if($stmt->execute()){
            $stmt->bind_result($id,$imageurl,$rating,$name,$store_location_name,$price);
            $foodDescription = array();
            $listOfFoodDescription = array();
            $i = 0;
            while($stmt->fetch()){
                $foodDescription['food_id'] = $id;
                $foodDescription['food_image_url']=$imageurl;
                $foodDescription['food_rating'] = $rating;
                $foodDescription['food_name'] = $name;
                $foodDescription['store_details_id'] = $store_location_name;
                $foodDescription['food_price'] =$price;
                $listOfFoodDescription[$i] = $foodDescription;
                $i++;
            }

            return $listOfFoodDescription;
        }

        return null;
    }

    private function isUserExit($email)
    {
        $query = "select id from users where email = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}