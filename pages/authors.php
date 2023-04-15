<?php 
  
  include "../db/conn.php";

  session_start();

  $token = $_SESSION['token'];
  
  if(!$token || $token == null) {
     return header('location:http://localhost:88/crud-php/pages/');
  }

  $find_user = $conn->query("SELECT * FROM user WHERE id = '$token'");
  $find_author = $conn->query("SELECT * FROM authors WHERE user_id = '$token'");

  $authors_data = [];

  if(mysqli_num_rows($find_user)) {
      $user = mysqli_fetch_assoc($find_user);
   
      for($x = 0; $x < mysqli_num_rows($find_author); $x++) {
          $result = mysqli_fetch_assoc($find_author);
          array_push($authors_data,$result);
      }
  }
  

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create author</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../styles/output.css">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet">
    </head>
    <body> 
      <div class="w-full min-h-screen bg-white py-5 px-5">
      <div class="w-[87%] mx-auto flex items-center justify-between relative">
            <a href="http://localhost:88/crud-php/pages/homepage.php" class="text-3xl font-extrabold">Mybrary</a>
            <ul class="flex items-center gap-x-5 mt-2">
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/homepage.php">Home</a></li>
                <li><a class="font-medium text-md text-blue-500" href="http://localhost:88/crud-php/pages/authors.php">Authors</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/createAuthor.php">Create author</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/books.php">Books</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/createBook.php">Create book</a></li>
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
         <div class="mb-14">
            <h2 class="text-xl font-bold">Search Authors</h2>
            <input  id="search" type="text" class="w-[40vw] bg-gray-100 py-2 px-3 rounded-full outline-none mt-5">
         </div>

         <div id="author-container" class="grid grid-cols-4 gap-3 mt-4">
          <?php for($y = 0; $y < count($authors_data); $y++) { ?>

            <div class="w-full shadow-lg py-4 px-5 rounded-md">
               <h2 class="text-xl font-bold"><?= $authors_data[$y]["author_name"] ?></h2>
               <p class="text-gray-600 text-sm mt-1 font-medium">Pen Name : <?= $authors_data[$y]["pen_name"] ?></p>
               <div class="flex items-center gap-x-3 mt-5">
                <button  data-id="<?= $authors_data[$y]["id"] ?>" class="delete-button bg-orange-500 text-white rounded-md py-2 px-3 font-semibold text-sm">Delete</button>
                <form method="GET" action="./createAuthor.php?id=<?= $authors_data[$y]['id'] ?>&type=update">
                <input type="hidden" name="id" value="<?= $authors_data[$y]['id'] ?>">
                <input type="hidden" name="type" value="update">
                <button type="submit" class="update-button bg-blue-500 text-white rounded-md py-2 px-3 font-semibold text-sm">Update</button>
            </form>
               </div>
            </div>

            <?php } ?>
      </div>
         </div>
  
      </div>
   
     

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

                    if(parsing_response.status === 200) {
                        window.location.href = "http://localhost:88/crud-php/pages/";
                    }
                }

                xml.open("GET", "../action/logout.php?id="+id,true);
                xml.send();
            }
        });

        function deleteAuthor() {
            const id = this.dataset.id;

            if(id){
                const xml = new XMLHttpRequest();

                xml.onreadystatechange = function() {
                    const parsing_response = JSON.parse(this.response);

                    if(parsing_response.status === 200) {
                        window.location.href = "http://localhost:88/crud-php/pages/authors.php";
                    }

                }

                xml.open("GET", "../action/author.php?type=delete&id="+id,true);
                xml.send();
            }
        }

      const deleteButton = document.querySelectorAll('.delete-button');;
      const updateButton = document.querySelectorAll('.update-button');

      deleteButton.forEach((button) => {
        button.addEventListener('click' , deleteAuthor);
      });

    const authorContainer = document.getElementById("author-container");
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
    let authors = [];

    xml.addEventListener("readystatechange" , function() {
   
      const {data} = this.response && JSON.parse(this.response);
      authors = data;

    });

    authorContainer.innerHTML = `
      <div>
        <h2 class="font-bold text-xl">Loading...</h2>
      </div>
    `

     setTimeout(() => {

        if(Array.isArray(authors) && authors.length > 0) {
            authors.map((author) => {
                temp += `
                <div class="w-full shadow-lg py-4 px-5 rounded-md">
               <h2 class="text-xl font-bold">${author.author_name}</h2>
               <p class="text-gray-600 text-sm mt-1 font-medium">Pen Name : ${author.pen_name}</p>
               <div class="flex items-center gap-x-3 mt-5">
                <button  data-id="" class="delete-button bg-orange-500 text-white rounded-md py-2 px-3 font-semibold text-sm">Delete</button>
                <form method="GET" action="./createAuthor.php?id=${author.id}&type=update">
                <input type="hidden" name="id" value="${author.id}">
                <input type="hidden" name="type" value="update">
                <button type="submit" class="update-button bg-blue-500 text-white rounded-md py-2 px-3 font-semibold text-sm">Update</button>
            </form>
               </div>
            </div>
              `;
            });

            authorContainer.innerHTML = temp;
        }

     },1200);

    xml.open("GET","../action/search.php?type=author&term="+document.getElementById("search").value,true);
    xml.send();
  }
 
  const processChange = debounce(()=>searchHandler());

  searchInput.addEventListener('keyup' , processChange);
  
      </script>
</body>
        </html>