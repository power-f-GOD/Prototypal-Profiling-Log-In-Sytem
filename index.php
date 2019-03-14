<?php

  session_start();
  

  //if user is/was already signed in, redirect to home page
  if (array_key_exists('u_0', $_SESSION))
  {
    $id = $_SESSION['u_0']['id'];
    header("Location: home?user=0&u_id=$id");
  }


  require "php/header.php";

  Using::IndexNavLinks();
  
?>


<!-- index-content -->
<div id="text-center">
  <h2 class="g-techly-text g-techly-header g-techly-bold-center">G-TECHLY</h2>
  <button id="get-started-button" class="button">GET STARTED</button>
</div>
    
    

<?php

  require "php/footer.php";
  
?>