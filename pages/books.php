<?php 
  
  include "../db/conn.php";

  session_start();

  $token = $_SESSION['token'];
  
  if(!$token || $token == null) {
     return header('location:http://localhost:88/crud-php/pages/');
  }

  $find_user = $conn->query("SELECT * FROM user WHERE id = '$token'");
  $books = array();

  if(mysqli_num_rows($find_user)) {
      $user = mysqli_fetch_assoc($find_user);
      $id = $user['id'];
      $query_books = $conn->query("SELECT * FROM books WHERE user_id = '$id' ");

      for($x = 0; $x < mysqli_num_rows($query_books); $x++) {
        array_push($books, mysqli_fetch_assoc($query_books));
     }
  }



?>

<!DOCTYPE html>
<html>
    <head>
        <title>Books</title>
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
                <li><a class="font-medium text-md text-blue-500" href="http://localhost:88/crud-php/pages/books.php">Books</a></li>
                <li><a class="font-medium  text-md" href="http://localhost:88/crud-php/pages/createBook.php">Create book</a></li>
                <button id="profile" class="ml-4 py-[4px] rounded-md bg-blue-500 flex items-center font-medium px-3 text-white">
                    <?= $user["username"] ?>
                    <i class="ri-arrow-down-s-fill text-xl"></i>
                </button>
            </ul>
            <div id="dropdown" style="display:none" class="absolute shadow-xl right-0 -bottom-[70px] bg-white py-4 px-5 rounded-md  flex-col gap-y-3">
                <button data-id="<?= $user["id"] ?>" id="logout" class="flex items-center gap-x-2">
            <input type="hidden" name="author_id" value="<?= $books[$y]['author_id'] ?>">

                <i class="ri-logout-box-r-line"></i>
                <p>Logout</p>
                </button>
        
            </div>
         </div>
         <div class="w-[87%] mx-auto py-14">
         <div class="mb-14">
            <h2 class="text-xl font-bold">Search Books</h2>
            <input id="search" type="text" class="w-[40vw] bg-gray-100 py-2 px-3 rounded-full outline-none mt-5">
         </div>
     <div id="book-container" class=" grid grid-cols-5 gap-3">
       <?php 

       for($y = 0; $y < count($books); $y++) {

       ?>

       
       <form method="GET" action="./book.php" class="w-full h-[360px]">
        <input type="hidden" name="id" value="<?= $books[$y]['book_id'] ?>">
        <input type="hidden" name="author_id" value="<?= $books[$y]['author_id'] ?>">
        <button type="submit" class="w-full h-full">
        <img src="../uploads/<?= $books[$y]["cover"] ?>" class="w-full h-full rounded-md object-cover">
        </button>
       </form>

       <?php } ?>
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

        const bookContainer = document.getElementById("book-container");
        const searchInput = document.getElementById("search");


    function debounce(func, timeout = 500){
    let timer;
    return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => { func.apply(this, args); }, timeout);
   };
  }


  function searchHandler() {
    const xml = new XMLHttpRequest();
 
    let temp = '';
    let books = [];

    xml.addEventListener("readystatechange" , function() {
   
      const {data} = this.response && JSON.parse(this.response);
      books = data;

    });

    bookContainer.innerHTML = `
      <div>
        <h2 class="font-bold text-xl">Loading...</h2>
      </div>
    `

     setTimeout(() => {

        if(Array.isArray(books) && books.length > 0) {
            books.map((book) => {
                temp += `
              <form method="GET" action="./book.php" class="w-full h-[360px]">
              <input type="hidden" name="id" value="${book.id}">
              <input type="hidden" name="author_id" value="${book?.author_id}">
              <button type="submit" class="w-full h-full">
              <img src="../uploads/${book.cover}" class="w-full h-full rounded-md object-cover">
              </button>
              </form>
              `;
            });

            bookContainer.innerHTML = temp;
        }

     },1200);

    xml.open("GET","../action/search.php?type=book&term="+document.getElementById("search").value,true);
    xml.send();
  }
 
  const processChange = debounce(()=>searchHandler());

  searchInput.addEventListener('keyup' , processChange)

        </script>
    </body>
</html>