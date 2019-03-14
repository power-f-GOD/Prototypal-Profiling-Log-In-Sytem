<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


//PHPMailer class source code gotten from GitHub @https://github.com/PHPMailer/PHPMailer
$_Mailer = new PHPMailer(true);



function Mailer($email, $subject, $message, $fullname)
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

?>