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

    public function registerUser($name,$email,$phone,$password,$birthday){
        if($this->isUserExit($email)){
            return 0;
        }else{
            $ePassword = md5($password);
            $query = "insert into customers
                            (customer_name, customer_email, customer_phone_number, customer_password, customer_birthday)
                            values(?,?,?,?,?);";

            try {
                $dateOfBirth = new DateTime($birthday);
                $birthday = $dateOfBirth->format('Y-m-d');
            } catch (Exception $e) {

            }
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("sssss",$name,$email,
                $phone,$ePassword,$birthday);

            if($stmt->execute()){
                return 1;
            }else{
                return 2;
            }
        }
    }

    public function login($email,$password){

        $ePassword = md5($password);
        $query = "select id, customer_name,customer_email, customer_phone_number,
                        customer_birthday,customer_image_url,customer_location
                         from customers where customer_email=? and customer_password=?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("ss",$email,$ePassword);
        if($stmt->execute()){
            $stmt->bind_result($id,$dFullname,$dEmail,$dContact,$dBirthDate,
                $dCustomerImage,$dCustomerLocation);
            $temp=array();
            while($stmt->fetch()){

                $temp['id'] = $id;
                $temp['full_name'] = $dFullname;
                $temp['email'] = $dEmail;
                $temp['contact']=$dContact;
                $temp['birth_date'] = $dBirthDate;
                $temp['customer_image_url'] = $dCustomerImage;
                $temp['customer_location'] = $dCustomerLocation;
            }


            return $temp;
        }

        return null;
    }

    public function getFoodCategory(){
        $query = "Select * from food_category;";
        $stmt = $this->con->prepare($query);
        if($stmt->execute()){
            $stmt->bind_result($id, $categoryName,$categoryImageUrl);
            $categoryList = array();
            $listOfCategories = array();
            $i = 0;
            while($stmt->fetch()){
                $categoryList['id'] = $id;
                $categoryList['category_name'] = $categoryName;
                $categoryList['category_image_url'] = $categoryImageUrl;
                $listOfCategories[$i] = $categoryList;
                $i++;
            }

            return $listOfCategories;
        }
    }


    public function getFoodDescription($categoryID){
        $query = "select * from food where food_category_id = ?; ";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("i",$categoryID);
        if($stmt->execute()){
            $stmt->bind_result($id,$foodName,$foodImageUrl,$foodRating,
                $foodDescription,$foodCategoryID);
            $foodList = array();
            $listOfFoodDescription = array();
            $i = 0;
            while($stmt->fetch()){
                $foodList['food_id'] = $id;
                $foodList['food_name']=$foodName;
                $foodList['food_image_url'] = $foodImageUrl;
                $foodList['food_rating'] = $foodRating;
                $foodList['food_description'] = $foodDescription;
                $foodList['food_category_id'] =$foodCategoryID;
                $listOfFoodDescription[$i] = $foodList;
                $i++;
            }

            return $listOfFoodDescription;
        }

        return null;
    }

    public function getFoodDetails($foodId){
        $query = "select * from food_details where food_id = ?;";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("i",$foodId);

        if($stmt->execute()){
            $stmt->bind_result($foodDetailsId,$foodDetailsPrice,$foodDetailsSizes,
                $foodDetailsFoodId);
            $foodDetails = array();
            $foodDetailsList = array();
            $i = 0;
            while ($stmt->fetch()){
                $foodDetails['id'] = $foodDetailsId;
                $foodDetails['food_prices'] = $foodDetailsPrice;
                $foodDetails['food_sizes'] = $foodDetailsSizes;
                $foodDetails['food_id'] = $foodDetailsFoodId;
                $foodDetailsList[$i] = $foodDetails;
                $i++;
            }

            return $foodDetailsList;
        }

        return null;
    }

    public function uploadCart($customerName,$customerEmail,$customerPhone,
                            $foodName, $foodOptions,$foodQuantity,$foodPrice)
    {

        $customerIdentityNo = null;
        $detailIdentityNo = null;
        $foodIdentityNo = null;


        $customerIdQuery = "select id from customers where customer_name = ? 
                        and customer_email = ? and customer_phone_number = ?; ";
        $foodIdQuery = "select id from food where food_name=?";
        $detailsQuery = "select id from food_details where food_id = ? and food_sizes =?";


        $customerIdStmt = $this->con->prepare($customerIdQuery);
        $foodIdStmt = $this->con->prepare($foodIdQuery);
        $detailsStmt = $this->con->prepare($detailsQuery);

        $customerIdStmt->bind_param("sss",$customerName,$customerEmail,$customerPhone);
        $customerIdStmt->bind_result($customerId);
        if($customerIdStmt->execute()){
            while ($customerIdStmt->fetch()){
                $customerIdentityNo = $customerId;
            }
        }


        $foodIdStmt ->bind_param("s",$foodName);
        $foodIdStmt->bind_result($foodId);
        if($foodIdStmt->execute()){
            while ($foodIdStmt->fetch()){
                $foodIdentityNo = $foodId;
            }
        }

        $detailsStmt->bind_param("is",$foodIdentityNo,$foodOptions);
        $detailsStmt->bind_result($detailsId);
        if($detailsStmt->execute()){
            while ($detailsStmt->fetch()){
                $detailIdentityNo = $detailsId;
            }
        }

        $returnedValue = $this->insertCartIntoDatabase($customerIdentityNo,$foodIdentityNo,$detailIdentityNo,
            $foodQuantity,$foodPrice);

       return $returnedValue;

    }

    private function insertCartIntoDatabase($customerIdentityNo,$foodIdentity,
                                            $detailIdentityNo,$foodQuantity,$food_total_price){
        $query = "insert into cart
                            (customer_id, food_id, food_details_id, food_quantity, food_total_price)
                            values(?,?,?,?,?);";

        $stmt = $this->con->prepare($query);
        $stmt->bind_param("iiiid",$customerIdentityNo,$foodIdentity,
            $detailIdentityNo,$foodQuantity,$food_total_price);

        if($stmt->execute()){
            return 1;
        }else{
            return 2;
        }
    }

    private function isUserExit($email)
    {
        $query = "select id from customers where customer_email = ?";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}