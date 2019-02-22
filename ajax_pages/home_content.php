<?php

  require '../php/dbconfig.php';
  
  session_start();

  
  require '../php/utilities.php';
  

  $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
  if (!is_numeric($user_index))
    $user_index = 0;

?>


<div id="index-content">
  <h2 class="g-techly-text g-techly-header g-techly-bold-center">G-TECHLY</h2>
  <h3>Hey, <?php echo $_SESSION["u_$user_index"]["firstname"]; ?>!</h3>
  <i class='greeting'>Good morning!</i>
</div>