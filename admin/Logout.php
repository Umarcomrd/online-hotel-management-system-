<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <button 
   onclick=" 
subButton();


  "
  
  class = 'js-btn'>subscribe</button> 
  
  
  <script>

function subButton (){
  const sub = document.querySelector('.js-btn');

if(sub.innerHTML === 'subcribe'){

sub.innerHTML = 'subscibed';

} else{

    sub.innerHTML = 'subscibe';
}

}


  </script>

























</body>
</html>
   <?php
// admin/logout.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all admin session data
session_unset();
session_destroy();





// Redirect to admin login
header('Location: login.php');
exit;