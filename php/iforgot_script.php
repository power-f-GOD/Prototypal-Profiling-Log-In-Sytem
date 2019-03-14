<?php
  session_start();
  
  require_once "dbconfig.php";


  //stop script if there be any error in database connection
  if ($mysql->connect_error)
    die("Error: " . $mysql->connect_error);
  

  $JSON_responses = array();//array to hold all responses for user input
  

  require_once "utilities.php";


 
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    //validation for link email
    if (isset($_POST["iforgot-email"]))
    {
      $iforgot_email = strtolower($_POST["iforgot-email"]);

      if (empty($iforgot_email))
        Utils::appendJSONResponse("iforgot-email", "", $processing, "Kindly enter your email.", true);
      else if (!(strlen($iforgot_email) > 50) && (preg_match("/^\w+[\w\d.]*[\w\d]+@\w+\.[\w\d.]+[\w\d]$/i", $iforgot_email)))
        $iforgot_email = Utils::sanitize($iforgot_email);
      else
        Utils::appendJSONResponse("iforgot-email", "", $error, "⚠ Invalid email.", true);
    }



    $sql = "SELECT * FROM _users WHERE _email = '$iforgot_email';";

    if (isset($_POST["send-link-button"]) && $form_is_validated)
    {
      $email_exists = $mysql->query($sql);

      if ($email_exists->num_rows)
      {
        //send email confirmation link
        $data = $email_exists->fetch_assoc();
        $hash = $data['_hash'];
        $firstname = $data['_firstname'];
        $to = $iforgot_email;
        $subject = "Password Reset";
        $message = "
          <h3>Hello, $firstname!</h3>
          <br /><br />
          Please, click the link below to reset your password.
          <br /><br />
          http://localhost/g-techly/reset?email=$iforgot_email&hash=$hash
          <br /><br /><br /><br />
          G-TECHLY &copy; 2019
        ";

        try
        {
          //send mail
          Utils::Mailer($to, $subject, $message, $firstname);
          
          //send successful response to client
          Utils::appendJSONResponse("send-link-button", "", $success, "Confirmation link has just been sent to your email, <b>'$to'</b>. Tap the link to reset your password.<br /><br /> Thank you.<br /><br /><br /><b>― G-TECHLY Team</b>", false);
        } 
        catch (Exception $e)
        {
          //send failure response to client
          Utils::appendJSONResponse("send-link-button", "", $error, "Confirmation link could not be sent. Please try again later.", false);
        }
      }
      else Utils::appendJSONResponse("iforgot-email", "", $error, "⚠ Sorry, no account associated with '$iforgot_email'.", false);
    }

    $JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
    echo json_encode($JSON_responses); //send JSON stringified object of response objects
  }
?>