<?php 
  
  include "../db/conn.php";

  session_start();

  $token = $_SESSION['token'];
  
  if(!$token || $token == null) {
     return header('location:http://localhost:88/crud-php/pages/');
  }

  $query_author = $conn->query("SELECT * FROM authors");
  $find_user = $conn->query("SELECT * FROM user WHERE id = '$token'");
  $authors = array();

  if(mysqli_num_rows($find_user) > 0) {
      $user = mysqli_fetch_assoc($find_user);
       
      for($x = 0; $x < mysqli_num_rows($query_author); $x++) {
          array_push($authors, mysqli_fetch_assoc($query_author));
      }
  }

  $title = $author = $pages = $description = $cover = null;
  $id = null;
  $type = "create";

  if(isset($_GET['id']) && isset($_GET['type'])) {
      $type = $_GET['type'];
      $id = $_GET['id'];
      $query_book = $conn->query("SELECT * FROM books WHERE book_id = '$id' ");

      $book = mysqli_fetch_assoc($query_book);

      $title = $book['title'];
      $author = $book['author_id'];
      $pages = $book["pages"];
      $description = $book['description'];
      $cover = $book['cover'];
  }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Book</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet">
    </head>
    <body>
        <div class="w-full py-5 px-5 min-h-screen">
        <div class="w-[87%] mx-auto flex items-center justify-between relative">
            <a href="http://localhost:88/crud-php/pages/homepage.php" class="text-3xl font-extrabold">Mybrary</a>
            <ul class="flex items-center gap-x-5 mt-2">
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/homepage.php">Home</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/authors.php">Authors</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/createAuthor.php">Create author</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/books.php">Books</a></li>
                <li><a class="font-medium text-blue-500 text-md" href="http://localhost:88/crud-php/pages/createBook.php">Create book</a></li>
                <button id="profile" class="ml-4 py-[4px] rounded-md bg-blue-500 flex items-center font-medium px-3 text-white">
                    <?= $user["username"] ?>
                    <i class="ri-arrow-down-s-fill text-xl"></i>
                </button>
            </ul>
            <div id="dropdown" style="display:none" class="absolute shadow-xl right-0 -bottom-[70px] bg-white py-4 px-5 rounded-md  flex-col gap-y-3">
                <button data-id="<?= $user["id"] ?>" id="logout" class="flex items-center gap-x-2">
                <i class="ri-logout-box-r-line"></i>
                <p>Logout</p>
                </button>
        
            </div>
         </div>
         <div class="w-[87%] mx-auto py-14">
            <div class="w-[60vw] flex items-center flex-wrap gap-3">
                <input type="hidden" name="id" id="book_id" value="<?= $book["book_id"] ?>">
                <div class="w-[47%] flex flex-col items-start gap-y-2">
                    <label class="font-semibold text-sm">Title</label>
                    <input value="<?=$title ?>" id="title" type="text" class="w-full outline-none bg-gray-100 py-2 px-3 rounded-md">
                </div>
                    <div class="w-[47%] flex flex-col items-start gap-y-2">
                    <label class="font-semibold text-sm">Author</label>
                    <select id="author" type="text" class="outline-none w-full bg-gray-100 py-2 px-3 rounded-md">
                        <?php 
                          for($y = 0; $y < count($authors); $y++){
                        ?>
 
                        <option <?php if($authors[$y]["id"] == $author) { ?> selected <?php } ?> value="<?= $authors[$y]["id"] ?>"><?= $authors[$y]["author_name"] ?></option>

                        <?php } ?>
                    </select>
                </div>
                <div class="w-[47%] flex flex-col items-start gap-y-2">
                    <label class="font-semibold text-sm">Pages</label>
                    <input value="<?= $pages ?>" id="pages" type="number" class="w-full outline-none bg-gray-100 py-2 px-3 rounded-md">
                </div>
                <div class="w-[47%] flex flex-col items-start gap-y-2">
                    <label class="font-semibold text-sm">Description</label>
                    <textarea value="<?= $description ?>" id="description" type="text" class="outline-none w-full h-[100px] bg-gray-100 py-2 px-3 rounded-md"><?= $description ?></textarea>
                </div>
                <div class="w-[47%]">
                    <p class="font-semibold text-sm align-self-end">Image</p>
                    <input type="file" name="image" id="image" class="hidden">
                    <label for="image" class=" mt-3 bg-gray-100 rounded-md py-2 px-3 flex items-center justify-center w-full h-[210px] cursor-pointer" id="image-preview">
                    <?php if( $cover != null) { ?>
                        <img src="../uploads/<?= $cover ?>" class="w-full h-full rounded-md">
                    <?php } else { ?>
                        <i class="ri-image-add-line text-5xl text-gray-500"></i>
                        <?php } ?>
                    </label>
                </div>
                <div class="w-[100%] mt-3 flex items-center gap-x-2">
                    <button class="bg-orange-500 text-white font-semibold text-sm py-2 px-3 rounded-md">Cancel</button>
                    <button data-type="<?= $type ?>" data-id="<?= $user['id'] ?>" id="submit" class="bg-blue-500 text-white font-semibold text-sm py-2 px-3 rounded-md capitalize"><?php echo $type  ?></button>
                </div>
            </div>
         </div>
        </div>
        <script src="https://cdn.tailwindcss.com"></script>   
        <script>

        const dropdown = document.querySelector('#dropdown');
        const profileButton = document.getElementById("profile");
        const logoutButton = document.getElementById("logout");


        profileButton.addEventListener('click' , function() {

            if(dropdown.style.display === "none") {
                dropdown.style.display = "flex";
            } else {
                dropdown.style.display = "none";
            }
        });

        logoutButton.addEventListener('click' , function() {
            const id = this.dataset.id;

            if(id){
                const xml = new XMLHttpRequest();

                xml.onreadystatechange = function() {
                    const parsing_response = JSON.parse(this.response);

                    console.log(parsing_response);

                    if(parsing_response.status === 200) {
                        window.location.href = "http://localhost:88/crud-php/pages/";
                    }
                }

                xml.open("GET", "../action/logout.php?id="+id,true);
                xml.send();
            }
        });

        let imageFiles = null;
        
        const submitButton = document.getElementById("submit");
        const titleEl = document.getElementById("title");
        const descriptionEl = document.getElementById("description");
        const pagesEl = document.getElementById("pages");
        const authorEl = document.getElementById("author");
        const imageEl = document.getElementById("image");

        const imagePreview = document.getElementById("image-preview");

        function imageHandler(e) {
            const files =  e.target.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                 imagePreview.innerHTML = `
                   <img src="${reader.result}" class="w-full h-full rounded-md">
                 `;

                 imageFiles = files;
            }

            reader.readAsDataURL(files);
        }

        function createBook() {
            const id = this.dataset.id;
            const  type = this.dataset.type;
            const xml = new XMLHttpRequest();
            const formData = new FormData();

            if(id){
            formData.append("title" , titleEl.value);
            formData.append("description"  ,descriptionEl.value);
            formData.append("pages" , pagesEl.value);
            formData.append("author",authorEl.value);
            formData.append("image" , imageFiles);
            formData.append("type" ,`${type === "update" ? "update" : "create"}`);
            formData.append("id",  `${type === "update" ? document.getElementById("book_id").value : id}`);

                xml.onreadystatechange = function() {
                const parsing_response = JSON.parse(this.response);

                if(parsing_response.status === 200) {
                    window.location.href = "http://localhost:88/crud-php/pages/books.php";
                }
            }
            

            xml.open("POST", "../action/book.php" , true);
            xml.send(formData);

            imageFiles = null;
            
            imagePreview.innerHTML = `
            <i class="ri-image-add-line text-5xl text-gray-500"></i>
            `;
            }
        }  

        imageEl.addEventListener("change" , imageHandler);
        submitButton.addEventListener("click" , createBook);

        </script>
    </body>
</html>