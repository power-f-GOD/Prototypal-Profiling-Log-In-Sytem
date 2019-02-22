<?php

  require_once "dbconfig.php";


  session_start();

  $user_index = 0;
  while (array_key_exists("u_$user_index", $_SESSION))
    $user_index++;


  //stop script if there be any error in database connection
  if ($mysql->connect_error)
    die("Error: " . $mysql->connect_error);
  

 
  $uploads_dir = "../uploads/"; //this will be later changed in script to "./uploads/" after image has been moved to target directory by move_uploaded_file() function in order to fix client side CSS "background-image" property URL pathname error for uploaded image
  $image = $firstname = $lastname = $email = $username = $phone = $DOB = $password = $confirmPassword = "";
  $JSON_responses = array();//array to hold all JSON responses for user input



  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    require_once "validate_script.php";

    //if form is submitted, proceed
    if (isset($_POST["submit"]))
    {
      if ($form_is_validated)
      {
        $signup_date = date("Y-m-d H:i:s");
        $signed_in = 0;
        $account_active = 0;
        $hash = md5(rand(0, 1000));
        $password = password_hash($password, PASSWORD_BCRYPT);


        $user_is_successfully_signed_up = $mysql->query("INSERT INTO _users VALUES (null, '$firstname', '$lastname', '$username', '$email', '$phone', '$DOB', '$password', '$image_path', '$signup_date', '$signup_date', '$signed_in', '$account_active', '$hash');");

        if ($user_is_successfully_signed_up)
        {
          //send email confirmation link
          $to = $email;
          $subject = "Account Verification";
          $message = "
            <h3>Hi, $firstname!</h3>
            <br />
            Thanks for signing up!
            <br /><br />
            Please, click the link below to activate your account.
            <br /><br />
            http://localhost/g-techly/verify?user=$user_index&email=$email&hash=$hash
            <br /><br /><br /><br />
            G-TECHLY &copy; 2019
          ";

          try
          {
            Utils::Mailer($to, $subject, $message, "$firstname $lastname");
            //send successful signup response to client
            Utils::appendJSONResponse("submit", "", $success, "Thank you, <b>$firstname</b>, for signing up. :) <br /><br />A verification link has just been sent to your email. Tap the verification link to activate your account.<br /><br /> Thanks.<br /><br /><br /><b>― G-TECHLY Team</b>", false);
          } 
          catch (Exception $e) 
          {
            //send failure response to client
            Utils::appendJSONResponse("submit", "", $error, "Verification link could not be sent. Please try again later.", false);
          }
        }
        else
          Utils::appendJSONResponse("submit", "", $error, "⚠ Error: " . $mysql->error, false);
      }
    }
  
    $JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
    echo json_encode($JSON_responses); //send object of response objects
  }

  $mysql->close();
?>