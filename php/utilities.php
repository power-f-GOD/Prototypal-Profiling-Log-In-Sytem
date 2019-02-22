<?php

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';


  //PHPMailer class source code gotten from GitHub @https://github.com/PHPMailer/PHPMailer
  $_Mailer = new PHPMailer(true);
  


  $fieldEmptyMsg = "⚠ Field empty. Field required.";
  $processing = "processing";
  $error = "error";
  $success = "success";
  $form_is_validated = true;
  



  //class to be instatiated for JSON responses
  class Feedback
  {
    public $name = "";
    public $value = "";
    public $type = "success";
    public $message = "Name is valid.";
    public $field_required = true;
  }



  
  class Utils
  {
    //sanitize user input data for security reasons
    public static function sanitize($data)
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }


    //appends response(s) to $JSON_responses array before sending to client side
    public static function appendJSONResponse($name, $value, $type, $message, $field_required)
    {
      $type = $type ? $type : "success";
      $feedback = new Feedback();
      $feedback->name = $name;
      $feedback->value = $value;
      $feedback->type = $type;
      $feedback->message = $message;
      $feedback->field_required = $field_required;
      $GLOBALS["JSON_responses"][$name] = json_encode($feedback);

      if ($field_required && $type != "success")
        $GLOBALS["form_is_validated"] = false;
    }


    //format display of DATE and DATETIME sent from database to client side
    public static function formatDate($date_time)
    {
      $months = array("January", "February", "March", "April", "May", "June",
                  "July", "August", "September", "October", "November", "December");

      $datetime_splited = $date = $time = $am_or_pm = $hour = "";
      $formatted_date = $date_time;

      if (is_string($date_time) && preg_match("/\d+\-\d+\-\d+/", $date_time))
      {
        $datetime_splited = explode(" ", $date_time); 
        $date = explode("-", $datetime_splited[0]);
        $month_index = ((int) $date[1]) - 1;
        $day = (int) $date[2];
        $year = $date[0];

        if (array_key_exists(1, $datetime_splited)) //i.e. if date_time is of DATETIME type
        {
          $time = explode(":", $datetime_splited[1]);
          $am_or_pm = $time[0] > 11 ? "PM" : "AM";
          $hour = $time[0] > 12 ? $time[0] - 12 : ($time[0] == 0 ? 12 : $time[0]);
          $formatted_date = "<i>$months[$month_index] $day, $year at $hour:$time[1]$am_or_pm</i>";
        }
        else //else date_time is of DATE type (DOB expected)
          $formatted_date = "$months[0] $day, $year";
      }

      return (object) array("formatted" => $formatted_date, "unformatted" => $date_time);
    }


    //PHPMailer function for sending emails
    public static function Mailer($email, $subject, $message, $fullname)
    {
      global $_Mailer;
      
      //Server settings
      $_Mailer->SMTPDebug = 2;     // Enable verbose debug output
      $_Mailer->isSMTP();      // Set mailer to use SMTP
      $_Mailer->Host = 'smtp.gmail.com';      // Specify main and backup SMTP servers
      $_Mailer->SMTPAuth = true;     // Enable SMTP authentication
      $_Mailer->Username = 'root.user.power@gmail.com';     // SMTP username
      $_Mailer->Password = 'password@root';     // SMTP password
      $_Mailer->SMTPSecure = 'ssl';      // Enable TLS encryption, `ssl` also accepted
      $_Mailer->Port = 465;      // TCP port to connect to (25, 465, 587)

      //Recipients
      $_Mailer->setFrom('root.user.power@gmail.com', 'G-TECHLY');
      $_Mailer->addAddress($email, $fullname);     // Add a recipient
      /*
      $_Mailer->addAddress('ellen@example.com');     // Name is optional
      $_Mailer->addReplyTo('info@example.com', 'Information');
      $_Mailer->addCC('cc@example.com');
      $_Mailer->addBCC('bcc@example.com');

      //Attachments
      $_Mailer->addAttachment('/var/tmp/file.tar.gz');     // Add attachments
      $_Mailer->addAttachment('/tmp/image.jpg', 'new.jpg');      // Optional name
      */

      //Content
      $_Mailer->isHTML(true);      // Set email format to HTML
      $_Mailer->Subject = $subject;
      $_Mailer->Body    = $message;
      //$_Mailer->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $_Mailer->send();
    }
  }

?>