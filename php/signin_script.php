<?php
  session_start();
  
  require_once "dbconfig.php";


  //this is for a case where multiple tabs are opened and different/unique accounts are signed-in in all tabs i.e. it indexes all users (accounts)
  $user_index = 0;
  while (array_key_exists("u_$user_index", $_SESSION))
    $user_index++;


  //stop script if there be any error in database connection
  if ($mysql->connect_error)
    die("Error: " . $mysql->connect_error);
  

  $JSON_responses = array();//array to hold all responses for user input
  

  require_once "utilities.php";


  $login_id = $login_password = '';

 
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    //validation for login-id
    if (isset($_POST["login-id"]))
    {
      $login_id = strtolower($_POST["login-id"]);

      if (empty($login_id))
        Utils::appendJSONResponse("login-id", "", $processing, "Enter your G-TECHLY email or username.", true);
      else if (!(strlen($login_id) > 50) && (preg_match("/^[a-z0-9_.]+$/", $login_id) || preg_match("/^\w+[\w\d.]*[\w\d]+@\w+\.[\w\d.]+[\w\d]$/i", $login_id)))
        $login_id = Utils::sanitize($login_id);
      else
        Utils::appendJSONResponse("login-id", "", $error, "⚠ Invalid email or username.", true);
    }


    //validation for password
    if (isset($_POST["login-password"]))
    {
      $login_password = $_POST["login-password"];

      if (empty($login_password))
        Utils::appendJSONResponse("login-password", "", $processing, "Enter your password.", true);
      else if (strlen($login_password > 255))
        Utils::appendJSONResponse("login-password", "", $error, "✘ Wrong password.", true);
    }


    $sql = "SELECT * FROM _users WHERE _email = '$login_id' OR _username = '$login_id';";

    if (isset($_POST["signin-button"]) && $form_is_validated)
    {
      $account_exists = $mysql->query($sql);

      if ($account_exists->num_rows)
      {
        $data = $account_exists->fetch_assoc();

        if (password_verify($login_password, $data['_password']))
        {
          $user_signed_in = $mysql->query("UPDATE _users SET _signed_in = 1 WHERE _username = '$login_id' OR _email = '$login_id';");
          $user_info = ($mysql->query($sql))->fetch_assoc();

          //set user_index to current user_index accordingly if user is already signed in and tries to re-signin 
          foreach ($_SESSION as $user => $prop)
          {
            foreach ($_SESSION[$user] as $info => $val) 
              if ($info == 'email')
                if ($val == $user_info['_email'])
                {
                  $user_index = $_SESSION[$user]['user-index'];
                  Utils::appendJSONResponse("_email", "$val", "", "$user_index", false);
                }
          }

          foreach ($user_info as $prop => $value)
            if ($prop != "_password")
            {
              $_value = $value = !$value ? "―" : $value;

              if ($prop == "_dob")
              {
                $prop = "_DOB";
                $DOB_rearranged = explode("-", $value);
                $_value = $value != "―" ? "$DOB_rearranged[2]-$DOB_rearranged[1]-$DOB_rearranged[0]" : "―";
              }

              $refined_prop = str_replace("_", "-", substr_replace($prop, "", strpos($prop, "_"), 1));
              Utils::appendJSONResponse($refined_prop, ($refined_prop == "DOB" ? $value : $value), "success", $login_id, false);

              //store SESSION variables
              //check if value is of DATE or DATETIME
              if (preg_match("/\d+\-\d+\-\d+/", $value)) 
              {
                $formatted_date = Utils::formatDate($value)->formatted;
                $unformatted_date = Utils::formatDate($_value)->unformatted;
                $_SESSION["u_$user_index"]["$refined_prop"] = $formatted_date;
                $_SESSION["u_$user_index"]["unformatted-$refined_prop"] = $unformatted_date;
                Utils::appendJSONResponse($refined_prop, $formatted_date, "", "", false);
                Utils::appendJSONResponse("unformatted-$refined_prop", $unformatted_date, "", "", false);
              }
              else $_SESSION["u_$user_index"]["$refined_prop"] = $value;
            }

          $_SESSION["u_$user_index"]['user-index'] = $user_index; //set session user index
          Utils::appendJSONResponse("user-index", $user_index, '', '', false);
          $_firstname = $_SESSION["u_$user_index"]['firstname'];
          Utils::appendJSONResponse("signin-button", "", "", "Welcome, <b>$_firstname</b>.", false);
        }
        else Utils::appendJSONResponse("login-password", "0", $error, "✘ Password incorrect. Enter password again.", false);
      }
      else Utils::appendJSONResponse("login-id", "", $error, "⚠ Sorry, no such account exists. Sign up instead.", false);


      $JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
      echo json_encode($JSON_responses); //send JSON stringified object of response objects
    }
  }
?>