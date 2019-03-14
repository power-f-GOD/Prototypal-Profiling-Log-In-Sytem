<?php

  require "php/dbconfig.php";

  require "php/utilities.php";

  session_start();

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Account Activated - G-TECHLY</title>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
    <meta id='metallic' name='theme-color' content='#282828' />
    <!-- <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' /> -->
    <link rel='stylesheet' type='text/css' href='bootstrap/bootstrap.min.css' />
    <link rel='stylesheet' type='text/css' href='css/main.css' />
    <link rel='stylesheet' type='text/css' href='css/sign_in_up.css' />
    <link rel='stylesheet' type='text/css' href='css/profile.css' />
  </head>

  <body>
    <!-- nav menu -->
    <header class='fixed-top'>
      <nav class='navbar p-0 hide'>
        <!-- wrapper for icon and toggler button -->
        <div class='icon-toggler-wrapper w-100'>
          <a href='index.php'><h2 class='g-techly-text g-techly-icon header-icon'>G-TECHLY</h2></a>
        </div>
      </nav>
    </header>
      

<?php

  function message($type, $messageTitle, $message)
  {
    return "
      <main id='custom-container' class='hide'>
        <div class='feedback-wrapper signup-status-feedback-wrapper box-shadow'>
          <div class='feedback $type'>
            <h4 class='feedback-title'>$messageTitle</h4> 
            <span>$message</span>
          </div>
        </div>
      </main>";
  }


  if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash']))
  {
    //sanitize params to avoid security issues and vulnerabilities
    $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
    $email = Utils::sanitize($mysql->escape_string($_GET['email']));
    $hash = Utils::sanitize($mysql->escape_string($_GET['hash']));

    $account_inactive = $mysql->query("SELECT * FROM _users WHERE _email = '$email' AND _hash = '$hash' AND _account_active = 0;");

    if ($account_inactive->num_rows)
    {
      $firstname = ($account_inactive->fetch_assoc())['_firstname'];
      $mysql->query("UPDATE _users SET _account_active = 1 WHERE _email = '$email' AND _hash = '$hash';");

      echo message('success', '✔ Account Activated!', "Thank you, <b>$firstname</b>. Your account has been activated!<br />Please, <a href='signin?user=$user_index' style='color: blue;'>sign in</a> to continue.<br /><br /><br /><b>― G-TECHLY Team</b>");

      //update account-active SESSION variable if exists
      if (array_key_exists("u_$user_index", $_SESSION))
      {
        $_SESSION["u_$user_index"]['account-active'] = 1;
        $_SESSION["u_$user_index"]['email'] = $email;
        $_SESSION["u_$user_index"]['hash'] = $hash;
      }
    }
    else
      echo message('processing', '⚠ Notice!', "Your account has either already been activated or the URL is invalid. <br />Click <a href='signin?user=$user_index' style='color: blue;'>here</a> to sign in instead.");
  }
  else
    echo message('error', '⚠ Oops!', "Sorry, invalid parameters given for account verification.<br />Check your email for verification link or click <a href='signin?user=0' style='color: blue;'>here</a> to sign in.");

?>

    
    <!-- dark bg-overlay -->
    <div id='bg-overlay' class='hide'></div>
    
    <script type='text/javascript' src='js/jquery.js'></script>
    <script type='text/javascript' src='bootstrap/bootstrap.min.js'></script>
    <script type='text/javascript'>
      //fade in container and navbar on page load
      setTimeout(() => document.querySelector(".navbar").classList.remove("hide"), 1500);
      setTimeout(() => document.querySelector("#custom-container").classList.remove("hide"), 500);
    </script>
  </body>
</html>