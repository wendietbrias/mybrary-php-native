<?php 
  
  include "../db/conn.php";

  session_start();

  $token = $_SESSION['token'];
  
  if(!$token || $token == null) {
     return header('location:http://localhost:88/crud-php/pages/');
  }

  $find_user = $conn->query("SELECT * FROM user WHERE id = '$token'");

  if(mysqli_num_rows($find_user)) {
      $user = mysqli_fetch_assoc($find_user);
  }

  $author_name = null;
  $pen_name = null;
  $author_id = null;
  $type = "create";

  if(isset($_GET['id']) && isset($_GET['type'])) {
      $type = $_GET['type'];
      $author_id = $_GET['id'];
     
      $get_user = $conn->query("SELECT * FROM authors where id = '$author_id'");
      $fetch_user = mysqli_fetch_assoc($get_user);

      $author_name = $fetch_user["author_name"];
      $pen_name = $fetch_user["pen_name"];
  }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create author</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet">
    </head>
    <body> 
      <div class="w-full min-h-screen bg-white py-5 px-5">
      <div class="w-[87%] mx-auto flex items-center justify-between relative">
            <a href="http://localhost:88/crud-php/pages/homepage.php" class="text-3xl font-extrabold">Mybrary</a>
            <ul class="flex items-center gap-x-5 mt-2">
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/homepage.php">Home</a></li>
                <li><a class="font-medium text-md" href="http://localhost:88/crud-php/pages/authors.php">Authors</a></li>
                <li><a class="font-medium text-md text-blue-500" href="http://localhost:88/crud-php/pages/createAuthor.php">Create author</a></li>
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
         <div class="py-14 w-[87%] mx-auto">
        <h2 class="text-[25px] font-bold"><?php 
           
           if($type === "create") {
            echo "New";
           } else {
            echo "Update";
           }

        ?> Author</h2>
        <div class="form-author flex items-center flex-wrap mt-7 gap-x-4">
            <input type="hidden" value="<?= $author_id ?>" id="author_id">
            <div class="flex flex-col gap-y-3">
                <label class="font-semibold text-sm">Name Author</label>
                <input value="<?= $author_name ?>" id="name" class="bg-gray-100 outline-none py-[12px] px-3 w-[410px] rounded-md" type="text" name="name">
            </div>
            <div class="flex flex-col gap-y-3">
                <label class="font-semibold text-sm">Pen Name</label>
                <input value="<?= $pen_name ?>" id="penname" class="bg-gray-100 outline-none py-[12px] px-4 w-[410px] rounded-md" type="text" name="penname">
            </div>
            <div class="flex items-center gap-x-2 mt-7 w-full">
                <button id="cancel" class="bg-orange-500 text-[14px] text-white py-2 px-4 font-semibold rounded-md">Cancel</button>
                <button data-type="<?= $type ?>" data-id="<?= $user['id'] ?>" id="submit" class="bg-blue-500 text-[14px] text-white py-2 px-4 font-semibold rounded-md">Submit</button>

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

        const nameEl = document.getElementById("name");
        const submit = document.getElementById("submit");
        const cancel = document.getElementById("cancel");
        const pennameEl = document.getElementById("penname");

        function createAuthor() {
            const xml = new XMLHttpRequest();
            const id = this.dataset.id;
            const type = this.dataset.type;

            if(type === "create") {
                
            xml.onreadystatechange = function() {
                const parse_response = JSON.parse(this.response);

                if(parse_response.status === 200) {
                  return window.location.href = 'http://localhost:88/crud-php/pages/authors.php';
                }
            }
               
             xml.open("GET", `../action/author.php?type=create&name=${nameEl.value}&penname=${pennameEl.value}&id=${id}`,true);
             return xml.send();
            }

            xml.onreadystatechange = function() {
                const parse_response = JSON.parse(this.response);

                if(parse_response.status === 200) {
                  return window.location.href = 'http://localhost:88/crud-php/pages/authors.php';
                }
            }
            
            xml.open("GET", `../action/author.php?type=update&name=${nameEl.value}&penname=${pennameEl.value}&id=${document.getElementById("author_id").value}`,true)
            xml.send();
        }

        function cancelHandler() {
            nameEl.value = "";
            pennameEl.value = "";
        }

        submit.addEventListener('click' , createAuthor);
        cancel.addEventListener("click" , cancelHandler);

      </script>
</body>
        </html>