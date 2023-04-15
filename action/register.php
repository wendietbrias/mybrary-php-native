<?php 

include "../db/conn.php";

$email = $_REQUEST['email'];
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$confirm = $_REQUEST['confirm'];


if(isset($email) &&
   isset($username) &&
   isset($password) &&
   isset($confirm)
) {

    //check email if already exists
    $find_user = "SELECT * FROM user WHERE email = '$email' ";

    if(mysqli_num_rows($conn->query($find_user)) > 0) {
        echo json_encode(["message"=>"Account already exists", "status"=>400]);
    } else if($password != $confirm) {
        echo json_encode(["message"=>"password is not match" , "status"=>400]);
    } else {
        $hashing_password = password_hash("$password", PASSWORD_DEFAULT);
         
        if($hashing_password) {
             $create = $conn->query("INSERT INTO user (username,email,password)  VALUES('$username' ,'$email','$hashing_password')");

             if($create){
                echo json_encode(["message"=>"success create account"  ,"status"=>200]);
             }
        }
    }
    
} else {
     echo "Error while creating user";
}