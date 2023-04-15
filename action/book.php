<?php 

include "../db/conn.php";

$type = $title = $id = $description = $pages = $author = null;


if(
    isset($_REQUEST["title"]) &&
    isset($_REQUEST["pages"]) &&
    isset($_REQUEST["description"]) &&
    isset($_REQUEST["author"]) &&
    isset($_REQUEST["type"]) &&
    isset ($_FILES['image']) &&
    isset($_REQUEST["id"]) &&
    $_REQUEST['type'] === "create"
) {

    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES['image']["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $upload_broken = false;

    $type = $_REQUEST["type"];
    $title = $_REQUEST["title"];
    $description = $_REQUEST["description"];
    $pages = $_REQUEST["pages"];
    $author = $_REQUEST["author"];
    $id = $_REQUEST['id'];

    //upload image
    // Check if image file is a actual image or fake image
  if(isset($_FILES["image"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    
    if($check === false) {
        echo json_encode(["message"=>"File is not an image.","status"=>400]);
        $upload_broken = true;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo json_encode(["message"=>"Sorry, file already exists.","status"=>400]);
    $uploadOk = true;
  }
  
  // Check file size
  if ($_FILES["image"]["size"] > 500000) {
    echo json_encode(["message"=>"Sorry, your file is too large.","status"=>400]);
    $upload_broken = true;
  }

        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $url = htmlspecialchars( basename( $_FILES["image"]["name"]));

         $query_create = $conn->query("INSERT INTO books (title,pages,author_id,description,cover,user_id) VALUES('$title','$pages','$author','$description','$url','$id')");

         if($query_create) {
            echo json_encode(["message"=>"success create books" ,"status"=>200]);
         }

} else if(isset($_REQUEST["type"]) && isset($_REQUEST['id']) && $_REQUEST['type'] === "delete") {
    $id = $_REQUEST['id'];

    $query_delete = $conn->query("DELETE FROM books WHERE book_id = '$id' ");

    if($query_delete) {
        echo json_encode(['message'=>'success delete book' ,'status'=>200]);
    }
} else if(  
isset($_REQUEST["title"]) &&
isset($_REQUEST["pages"]) &&
isset($_REQUEST["description"]) &&
isset($_REQUEST["author"]) &&
isset($_REQUEST["type"]) &&
isset($_REQUEST["id"]) &&
$_REQUEST['type'] == "update"
) {
   $title = $_REQUEST['title'];
   $author = $_REQUEST['author'];
   $pages = $_REQUEST['pages'];
   $description = $_REQUEST['description'];
   $id = $_REQUEST['id'];

    if(isset($_FILES['image']) && $_FILES['image'] != null)  {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
              unlink($_FILES['image']['name']);
          }

          move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
          $url = htmlspecialchars( basename( $_FILES["image"]["name"]));
  
          $query_update  = $conn->query("UPDATE books SET title = '$title' , pages = '$pages' , cover = '$url' , description = '$description', author_id = '$author' WHERE book_id = '$id' ");
  
           if($query_update ) {
              echo json_encode(["message"=>"success update books" ,"status"=>200]);
           }

    } else {
        $query_update = $conn->query("UPDATE books SET title = '$title' , pages = '$pages'  , description = '$description', author_id = '$author' WHERE book_id = '$id' ");

        if($query_update) {
            echo json_encode(["message"=>"success update books" ,"status"=>200]);
        }
    }
} else {
    echo json_encode(['message'=>'error' , 'status'=>500]);
}
