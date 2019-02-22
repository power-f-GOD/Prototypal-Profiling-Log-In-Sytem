<?php

  require "dbconfig.php";

  session_start();


  $JSON_responses = array();


  require 'utilities.php';


  $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));


  if (!is_numeric($user_index))
    $user_index = 0;


  if (array_key_exists("u_$user_index", $_SESSION))
    foreach ($_SESSION["u_$user_index"] as $prop => $value)
      Utils::appendJSONResponse($prop, $value, "", "", false);
  else
    Utils::appendJSONResponse('signed-in', 0, '', '', false);


  Utils::appendJSONResponse('user', $user_index, "", "", false);


  $JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
  echo json_encode($JSON_responses); //send JSON stringified object of response objects
  
?>