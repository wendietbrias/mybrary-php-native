<?php 
include "../db/conn.php";

$id = $_REQUEST['id'];

if(isset($_REQUEST['type']) 
&& isset($_REQUEST['name']) 
&& isset($id) && isset($_REQUEST['type'])) {
    $type = $_REQUEST["type"];
    $name = $_REQUEST['name'];
    $penname = $_REQUEST['penname'];

    if($type === "create"){
        $query = "INSERT INTO authors (author_name,pen_name,user_id) VALUES('$name','$penname','$id') ";

        if($conn->query($query)) {
            echo json_encode(['status'=>200]);
        } else {
            echo json_encode(['status'=>400,'message'=>'failed while creating author']);
        }
    } else {
        $query = "UPDATE authors SET author_name = '$name', pen_name = '$penname' WHERE id='$id'";

        if($conn->query($query)) {
            echo json_encode(['status'=>200]);
        } else {
            echo json_encode(['status'=>400,'message'=>'failed while creating author']);
        }
    }
   
} else if(isset($_REQUEST['type']) && $id) {
        $type = $_REQUEST['type'];
        $query = "DELETE FROM authors WHERE id='$id' ";

         if($type === "delete") {
            if($conn->query($query)) {
                echo json_encode(['status'=>200]);
            } else {
                echo json_encode(['status'=>400,'message'=>'failed while creating author']);
            }
         }
}  