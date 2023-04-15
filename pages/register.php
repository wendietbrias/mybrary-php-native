<?php 

  session_start();

  $token = $_SESSION['token'];

  if($token != null) {
     header('location:http://localhost:88/crud-php/pages/homepage.php');
  }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body> 
        <div class="w-full min-h-screen bg-indigo-500 flex items-center justify-center">
           <div class="w-[29vw] py-5 px-5 bg-white rounded-[5px] shadow-md">
             <div id='alert-container' class="w-full"></div>
              <div class="text-center">
              <h4 class="text-[28px] font-bold capitalize">Register Form</h4>
              <p class="text-sm  font-medium text-gray-600">Create  your account easier and more benefits</p>
              </div>
              <form id='form-submit' class="mt-7 flex flex-col gap-y-2" method="POST">
              <div class="flex flex-col gap-y-2">
                  <input placeholder="Username" name="username" type="text" class="w-full py-[9px] px-3  rounded-[4px] ring-2 ring-gray-200 outline-none focus:ring-2  focus:ring-blue-400">       
                  <p style="display:none" class="text-red-500 font-medium"></p>
                 </div>
                 <div class="flex flex-col gap-y-2">
                  <input placeholder="Email Address" name="email" type="email" class="w-full py-[9px] px-3 ring-2 ring-gray-200  rounded-[4px]  outline-none focus:ring-2 focus:border-none focus:ring-blue-400">
                  <p style="display:none" class="text-red-500 font-medium"></p>
                 </div>
                 <div class="flex flex-col gap-y-2">
                  <input placeholder="Password" name="password" type="password" class="w-full py-[9px] px-3  rounded-[4px] ring-2 ring-gray-200 outline-none focus:ring-2  focus:ring-blue-400">       
                  <p style="display:none" class="text-red-500 font-medium"></p>

                 </div>
                 <div class="flex flex-col gap-y-2">
                  <input placeholder="Confirm Password" name="confirm" type="password" class="w-full py-[9px] px-3  rounded-[4px] ring-2 ring-gray-200 outline-none focus:ring-2  focus:ring-blue-400">       
                  <p style="display:none" class="text-red-500 font-medium"></p>

                 </div>
                 <button class="w-full rounded-md mt-4  bg-indigo-500 text-white font-semibold uppercase text-[16px] py-2" type="submit">Register</button>
              </form>
              <p class="text-sm font-medium text-gray-400 mt-3 text-center">Already have account? <a class="text-indigo-500 font-semibold" href="http://localhost:88/crud-php/pages/">Login</a></p>
           </div>
        </div>
    </body>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>

      const alertContainer = document.querySelector('#alert-container');
      const passwordEl = document.querySelector("#form-submit input[name='password']");
      const confirmEl = document.querySelector("#form-submit input[name='confirm']");
      const usernameEl = document.querySelector("#form-submit input[name='username']");
      const emailEl = document.querySelector("#form-submit input[name='email']");

      const formSubmit = document.getElementById('form-submit');

       function registerHandler(e) {
        e.preventDefault();

          const siblingPassword = passwordEl.nextElementSibling;
          const siblingUsername = usernameEl.nextElementSibling;
          const siblingEmail = emailEl.nextElementSibling;
          const siblingConfirm = confirmEl.nextElementSibling;

          if(passwordEl.value === "") {
             siblingPassword.innerHTML = "Password field is required";
             siblingPassword.style.display = "block";
          }

          if(usernameEl.value === "") {
            siblingUsername.innerHTML = "Username field is required";
            siblingUsername.style.display = "block";
          }

          if(emailEl.value === "") {
            siblingEmail.innerHTML = "Email field is required";
            siblingEmail.style.display = "block";
          }

          if(confirmEl.value === "") {
            siblingConfirm.innerHTML = "Confirm field is required";
            return siblingConfirm.style.display = "block";
          }
   

          alertContainer.innerHTML = `
                   <div class="w-full rounded-md py-2 px-3 mb-3 bg-blue-50 text-blue-500">
                     <h4 class="font-semibold text-md capitalize">${"Authenticating User..."}</h4>
                   </div>
                 `

          const xml = new XMLHttpRequest();
          xml.onreadystatechange = function() {
              const parsing_response = JSON.parse(this.response);


              if(parsing_response.status >= 400) {
                 alertContainer.innerHTML = `
                   <div class="w-full rounded-md py-2 px-3 mb-3 bg-red-50 text-red-500">
                     <h4 class="font-semibold text-md capitalize">${parsing_response.message}</h4>
                   </div>
                 `
              } else {
                alertContainer.innerHTML = `
                   <div class="w-full rounded-md py-2 px-3 mb-3 bg-green-50 text-green-500">
                     <h4 class="font-semibold text-md capitalize">${parsing_response.message}</h4>
                   </div>
                 `;

                 window.location.href = "http://localhost:88/crud-php/pages/";
              }
          }

          xml.open("GET", `../action/register.php?username=${usernameEl.value}&email=${emailEl.value}&password=${passwordEl.value}&confirm=${confirmEl.value}` , true);
          xml.send();
      }

      formSubmit.addEventListener('submit' , registerHandler);

    </script>
</html>