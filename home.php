<?php

  require 'php/dbconfig.php';
  
  session_start();


  //if not signed in, redirect to index (Get Started) page
  if (!array_key_exists('u_0', $_SESSION))
    header('Location: http://localhost/g-techly/index');


  $id = $_SESSION['u_0']['id'];


  //redirect to default user ($user_index = 0) profile home page if current $user_index is not signed in 
  if (!array_key_exists('user', $_GET))
    header("Location: http://localhost/g-techly/home?user=0&u_id=$id");
  

  require 'php/utilities.php';

  require "php/header.php";

  Using::ProfileHeader();


  $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));

?>


<!-- index-content -->
<div id="text-center">
  <h2 class="g-techly-text g-techly-header g-techly-bold-center">G-TECHLY</h2>
  <h3>Hey, <?php echo $_SESSION["u_$user_index"]["firstname"]; ?>!</h3>
  <i class='greeting'>Good morning!</i>
</div>

    
    
<!-- set client side browser sessionStorage variables and values -->
<?php

  $sess_vars = array("id", "firstname", "lastname", "username", "email", "phone", "DOB", "image-name", "signup-date", "last-modified-date", "signed-in", "account-active", "unformatted-DOB", 'hash');

  $signed_in = $_SESSION["u_$user_index"]["signed-in"];

  echo
  '<script type="text/javascript">';
    foreach ($sess_vars as $key => $var) 
      if (array_key_exists("$var", $_SESSION["u_$user_index"]))
      {
        $var_val = $_SESSION["u_$user_index"]["$var"];
        echo "sessionStorage.setItem('$var', '$var_val');\n";
      }
  echo
  "</script>";
  

  require "php/footer.php";
  
?>