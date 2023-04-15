<?php 
include "../db/conn.php";

if(isset($_REQUEST["type"]) && isset($_REQUEST["term"]) && $_REQUEST["type"] === "book") {
   $books = array();
   $term = $_REQUEST['term'];

     if($_REQUEST["term"] === "") {
          $query_books = $conn->query("SELECT * FROM books");

          for($x = 0; $x < mysqli_num_rows($query_books); $x++) {
             array_push($books,mysqli_fetch_assoc($query_books));
          }

          echo json_encode(["data"=>$books, "status"=>200]);
     } else {

        $query_books = $conn->query("SELECT * FROM books WHERE title LIKE '%$term%' ");
         
         if(mysqli_num_rows($query_books) === 0) {
            $books = array();
            $query_books = $conn->query("SELECT * FROM books");

             for($x = 0; $x < mysqli_num_rows($query_books); $x++) {
                array_push($books,mysqli_fetch_assoc($query_books));
             }
    
             echo json_encode(['data'=>$books,'status'=>200]);

        } else {
            $books = array();
            for($x = 0; $x < mysqli_num_rows($query_books); $x++) {
                array_push($books,mysqli_fetch_assoc($query_books));
             }
    
             echo json_encode(['data'=>$books,'status'=>200]);
        }

     }
} else if(
    isset($_REQUEST["type"]) && isset($_REQUEST["term"]) && $_REQUEST["type"] == "author"
) {
    $authors = array();
    $term = $_REQUEST['term'];
 
      if($_REQUEST["term"] === "") {
           $query_authors = $conn->query("SELECT * FROM authors");
 
           for($x = 0; $x < mysqli_num_rows($query_authors); $x++) {
              array_push($authors,mysqli_fetch_assoc($query_authors));
           }
 
           echo json_encode(["data"=>$authors, "status"=>200]);
      } else {
 
         $query_authors = $conn->query("SELECT * FROM authors WHERE author_name LIKE '%$term%' ");
          
          if(mysqli_num_rows($query_authors) === 0) {
             $authors = array();
             $query_authors = $conn->query("SELECT * FROM authors");
 
              for($x = 0; $x < mysqli_num_rows($query_authors); $x++) {
                 array_push($authors,mysqli_fetch_assoc($query_authors));
              }
     
              echo json_encode(['data'=>$authors,'status'=>200]);
 
         } else {
             $authors = array();
             for($x = 0; $x < mysqli_num_rows($query_authors); $x++) {
                 array_push($authors,mysqli_fetch_assoc($query_authors));
              }
     
              echo json_encode(['data'=>$authors,'status'=>200]);
         }
 
      }
}