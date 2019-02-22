<?php

  require_once "dbconfig.php";


  session_start();


  //stop script if there be any error in database connection
  if ($mysql->connect_error)
    die("Error: " . $mysql->connect_error);
  

  $password = $confirmPassword = "";
  $JSON_responses = array();//array to hold all JSON responses for user input



  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    require_once "validate_script.php";

    //if form is submitted, proceed
    if (isset($_POST["submit"]))
    {
      if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash']))
      {
        $email = Utils::sanitize($mysql->escape_string($_GET['email']));
        $hash = Utils::sanitize($mysql->escape_string($_GET['hash']));
        $password = password_hash($password, PASSWORD_BCRYPT);

        if ($form_is_validated)
        {
          $password_is_successfully_reset = false; 
          
          //check if account exists with 'SELECT' query since 'UPDATE' query always returns 'true' whether account exists or not
          $account_exists = ($mysql->query("SELECT * FROM _users WHERE _email = '$email' AND _hash = '$hash';"))->num_rows;

          if ($account_exists)
            $password_is_successfully_reset = $mysql->query("UPDATE _users SET _password = '$password' WHERE _email = '$email' AND _hash = '$hash';");

          if ($password_is_successfully_reset)
          {
            //send successful password reset response to client
            Utils::appendJSONResponse("submit", "", $success, "Your password has been successfully reset. You can now <a href='javascript:void(0)' style='color: blue;' class='js--nav-link reset-signin-link' data-href='ajax_pages/signin_content.php'>sign in</a> with your new password.<br /><br /><br /><b>― G-TECHLY Team</b><br />$password_is_successfully_reset", false);
          }
          else
            Utils::appendJSONResponse("submit", "", $error, "Invalid URL parameters given for password reset. No such account exists. Click <a href='javascript:void(0)' style='color: blue;' class='js--nav-link reset-signin-link' data-href='ajax_pages/signup_content.php'>here</a> to sign up instead.", false);
        }
      }
      else Utils::appendJSONResponse('submit', '', 'error', "⚠ Oops! Sorry, invalid parameters given for password reset.<br />Check your email for password reset link or click <a href='signin?user=0' style='color: blue;'>here</a> to sign in.", false);
    }
  
    $JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
    echo json_encode($JSON_responses); //send object of response objects
  }

  $mysql->close();
?>