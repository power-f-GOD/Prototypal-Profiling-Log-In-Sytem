<?php
  
  require "dbconfig.php";

  session_start();
  

  $JSON_responses = array();


  require "utilities.php";


  //get user index from URL
  $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));


  if (!array_key_exists("u_$user_index", $_SESSION))
    $user_index = 0;


  $id = $_SESSION["u_$user_index"]['id'];
  $email = $_SESSION["u_$user_index"]['email'];
  $hash = $_SESSION["u_$user_index"]['hash'];
  $user_is_signed_out = $mysql->query("UPDATE _users SET _signed_in = 0 WHERE _id = '$id' AND _email = '$email' AND _hash = '$hash';");


  if ($user_is_signed_out)
  {
    session_destroy();
    Utils::appendJSONResponse("sign-out", "sign-out", $success, "User is signed out.", false);
  }
  else Utils::appendJSONResponse("sign-out", "sign-out", $error, "Unable to sign user out.", false);
  

  $JSON_responses = (object) $JSON_responses;
  echo json_encode($JSON_responses);
  
?>