<?php 
include "../db/conn.php";

session_start();

$token = $_SESSION['token'];
  
if(!$token || $token == null) {
   return header('location:http://localhost:88/crud-php/pages/');
}

$find_user = $conn->query("SELECT * FROM user WHERE id = '$token'");
$id = null;
$book = $author_id = null;

if(isset($_GET['id']) && isset($_GET['author_id'])) {
    $user = mysqli_fetch_assoc($find_user);
    $id_user = $user['id'];
    $id = $_GET['id'];
    $author_id = $_GET['author_id'];

  $query_book = $conn->query("SELECT books.book_id, books.title, books.pages,books.cover,books.description, authors.author_name ,authors.pen_name FROM books  INNER JOIN authors ON books.author_id = authors.id AND book_id = '$id' ");

  if(mysqli_num_rows($query_book)) {
      $book = mysqli_fetch_assoc($query_book);

  }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Book - Detail</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
    </head>
    <body>
        <div class="w-full min-h-screen py-5 px-5 flex justify-center items-center">
           <div class="w-[52vw] flex items-start gap-x-7 relative">
             <img src="../uploads/<?= $book["cover"] ?>" class="w-[40%] rounded-md h-[450px]">
             <div class="flex-1">
                <h2 class="text-3xl font-bold"><?= $book["title"] ?></h2>
                <p class="text-gray-500 font-normal leading-6 mt-4">Description : <?= $book["description"] ?></p>
                <p class="text-gray-500 font-normalmt-2">Pages : <?= $book["pages"] ?></p>
                <p class="text-gray-500 font-normal  mt-2">Author : <?= $book["author_name"] ?></p>
                <div class="flex items-center gap-x-2 mt-7">
                    <button id="delete-button" data-id="<?= $book["book_id"] ?>" class="bg-orange-500 text-sm font-semibold py-2 px-3 rounded-md text-white">Delete</button>
                    <a href="http://localhost:88/crud-php/pages/books.php" class="bg-green-500 text-sm font-semibold py-2 px-3 rounded-md text-white">Back</a>
                    <form method="GET" action="./createBook.php">
                        <input type="hidden" name="id" value="<?= $book["book_id"]?>">
                        <input type="hidden" name="type" value="update">
                        <button type="submit" class="bg-blue-500 text-sm font-semibold py-2 px-3 rounded-md text-white">Update</button>
                    </form>
                </div>
             </div>
           </div>
        </div>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>

          const deleteButton = document.getElementById("delete-button");

          function deleteBook() {
              const id = this.dataset.id;
              const xml = new XMLHttpRequest();

              xml.onreadystatechange = function() {
                const data = this.response ? JSON.parse(this.response) : null;

                if(data.status === 200) {
                    window.location.href = "http://localhost:88/crud-php/pages/books.php";
                }
              }

              xml.open("GET"  , "../action/book.php?type=delete&id="+id,true);
              xml.send();
          }

          deleteButton.addEventListener("click" , deleteBook);

        </script>
    </body>
</html>