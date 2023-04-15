<?php 

session_start();

$token = $_SESSION['token'];
$id = $_REQUEST['id'];

if(isset($token) && isset($id)) {
    if($token == $id) {
        $_SESSION['token'] = null;
        echo json_encode(["status"=>200]);

    }
}