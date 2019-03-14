<?php

  require '../php/dbconfig.php';
  
  session_start();

  
  require '../php/utilities.php';
  

  $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
  if (!is_numeric($user_index))
    $user_index = 0;
  
  if (!isset($_SESSION["u_$user_index"]))
  {
    echo "<h2>Please, <a href='signin?user=0'>sign in</a>.</h2>";
    exit;
  }

?>


<div id="index-content">
  <h2 class="g-techly-text g-techly-header g-techly-bold-center">G-TECHLY</h2>
  <h3>Hey, <span class='greeting-firstname'><?php echo $_SESSION["u_$user_index"]["firstname"]; ?></span>!</h3>
  <i class='greeting'>Good morning!</i>
</div>