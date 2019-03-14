<?php

  require 'php/dbconfig.php';
  
  session_start();

  require 'php/utilities.php';


  if (isset($_GET['user']))
    $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
  else $user_index = 0;


  //if not signed in, redirect to index (Get Started) page
  if (!array_key_exists("u_$user_index", $_SESSION))
  {
    //use 'initial_requested_page' to store/set actual requested page url; user will then be redirected to sign in page if they are not signed in; then later redirected to actual page requested if finally signed in 
    $_SESSION['url']['initial_requested_page'] = 'home';
    header('Location: signin?user=0');
    exit;
  }


  //redirect to initially requested page when user wasn't signed in after sign in
  if (array_key_exists('url', $_SESSION))
  {
    if (isset($_GET['u_id']))
    {
      $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
      $u_id = Utils::sanitize($mysql->escape_string($_GET['u_id']));
      header('Location: ' . $_SESSION['url']['initial_requested_page'] . '?user=' . $user_index . '&u_id=' . $u_id);
      unset($_SESSION['url']); //uset to avoid a redirect loop
      exit;
    }
  }
    

  $id = $_SESSION["u_$user_index"]['id'];


  //redirect to default user ($user_index = 0) profile home page if current $user_index is not signed in 
  if (!array_key_exists('user', $_GET))
  {
    header("Location: home?user=$user_index&u_id=$id");
    exit;
  }
    

  require "php/header.php";
  
  Using::ProfileNavLinks();

?>


<!-- index-content -->
<div id="text-center">
  <h2 class="g-techly-text g-techly-header g-techly-bold-center">G-TECHLY</h2>
  <h3>Hey, <span class='greeting-firstname'><?php echo $_SESSION["u_$user_index"]["firstname"]; ?></span>!</h3>
  <i class='greeting'>Good morning!</i>
</div>

    
<?php

  require "php/footer.php";
  
?>