
<?php

require "dbconfig.php";

session_start();


$JSON_responses = array();


require 'utilities.php';


$user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
$id = Utils::sanitize($mysql->escape_string($_GET['u_id']));


if (!isset($_SESSION["u_$user_index"]))
  goto error_report;


if (!is_numeric($user_index))
  $user_index = 0;


$email = $_SESSION["u_$user_index"]['email'];
$hash = $_SESSION["u_$user_index"]['hash'];

$account_exists = $mysql->query("SELECT * FROM _users WHERE _id = '$id' AND _email = '$email' AND _hash = '$hash';");


if ($account_exists->num_rows)
{
  $mysql->query("DELETE FROM _users WHERE _id = '$id' AND _email = '$email' AND _hash = '$hash';");

  //delete user image from server to free space
  unlink('.' . ($account_exists->fetch_assoc())['_image_name']);
  
  Utils::appendJSONResponse('delete-account', '✔ Account Deleted', '', "Your account has been deleted. You may click <a href='signup'>here</a> to create a new one.", false);
  
  session_destroy();
  $_SESSION = array(); //force session destroy if there be any cookie saved
  
  goto send_report;
}


error_report: 

Utils::appendJSONResponse('delete-account', '⚠ Account Inexistent', '', "Your account has already been deleted and no longer exists. You may click <a href='signup'>here</a> to create a new account.", false);

send_report:

$JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
echo json_encode($JSON_responses); //send JSON stringified object of response objects

?>
