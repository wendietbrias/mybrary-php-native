<?php 
include "../db/conn.php";

session_start();
 
$email = $_REQUEST['email'];
$password = $_REQUEST['password'];

if(isset($email) && isset($password)) {

    $find_user = $conn->query("SELECT * FROM user WHERE email = '$email' ");

    if(mysqli_num_rows($find_user) > 0 && $row = mysqli_fetch_assoc($find_user)) {
        $password_verify = password_verify($password, $row["password"]);

        if($password_verify) {
            $_SESSION['token'] = $row["id"];
            echo json_encode(["message"=>"login success" , "status"=>200]);
        } else {
            echo json_encode(["message"=>"password wrong! try again" , "status"=>400]);
        }
    } else {
        echo json_encode(["message"=>"account is not found" , "status"=>400]);

    }

} else {
    echo json_encode(["message"=>"something wen't wrong" , "status"=>400]);
}