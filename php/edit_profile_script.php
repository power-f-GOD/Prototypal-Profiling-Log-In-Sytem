<?php

  require_once "dbconfig.php";

  session_start();


  //stop script if there be any error in connecting to database;
  if ($mysql->connect_error)
    die("Error: " . $mysql->connect_error);


  $user_index = $mysql->escape_string($_GET['user']);
  if (!array_key_exists("u_$user_index", $_SESSION))
    $user_index = 0;


  $uploads_dir = "../uploads/";
  $image = $image_path = $firstname = $lastname = $email = $username = $phone = $DOB = $password = $confirmPassword = "";
  $JSON_responses = array();//associative array to hold all responses for user input



  //SQL update function for profile edit
  function profile_updated($column, $data, $user_id)
  {
    global $user_index, $mysql;
    $last_modified_date = date("Y-m-d H:i:s");

    if ($column == '_password')
      $data = password_hash($data, PASSWORD_BCRYPT);
    
    $updated = $mysql->query("UPDATE _users SET $column = '$data', _last_modified_date = '$last_modified_date' WHERE _id = '$user_id';");

    if ($updated)
    {
      $get_last_mod_date = $GLOBALS["mysql"]->query("SELECT _last_modified_date FROM _users WHERE _id = '$user_id';");
      $last_mod_date = $get_last_mod_date->fetch_assoc();
      $last_mod_date = Utils::formatDate($last_mod_date["_last_modified_date"])->formatted;
      $_SESSION["u_$user_index"]["last-modified-date"] = $last_mod_date;
      Utils::appendJSONResponse("last-modified-date", $last_mod_date, "", "$updated", false);

      return true;
    }

    return false;
  }



  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    require "validate_script.php";
    
    //if form is submitted, proceed
    if (isset($_POST["submit"]))
    {
      if ($form_is_validated && isset($_POST["current-password"]) && isset($_SESSION["u_$user_index"]))
      {
        $user_id = $_SESSION["u_$user_index"]["id"];
        $current_password = $_POST["current-password"];
        $data = $mysql->query("SELECT * FROM _users WHERE _id = '$user_id';");

        //check if password matches account
        if (password_verify($current_password, ($data->fetch_assoc())['_password']))
        {
          //using "isset()" for all variables to prevent unwanted responses from being set to client

          //check if profile image updated
          if (isset($_FILES["profile-edit-image-file"]) && profile_updated("_image_name", $image_path, $user_id))
          {
            $_SESSION["u_$user_index"]["image-name"] = $image_path;
            Utils::appendJSONResponse("image-name", $image_path, $success, "✔ Profile image updated and saved.", false);
          }
            
          //check if firstname updated
          if (isset($_POST["firstname"]) && profile_updated("_firstname", $firstname, $user_id))
          {
            $_SESSION["u_$user_index"]["firstname"] = $firstname;
            Utils::appendJSONResponse("firstname", $firstname, $success, "✔ Firstname updated and saved.", false);
          }
            
          //check if lastname updated
          if (isset($_POST["lastname"]) && profile_updated("_lastname", $lastname, $user_id))
          {
            $_SESSION["u_$user_index"]["lastname"] = $lastname;
            Utils::appendJSONResponse("lastname", $lastname, $success, "✔ Lastname updated and saved.", false);
          }
            
          // check if username updated
          if (isset($_POST["username"]) && profile_updated("_username", $username, $user_id))
          {
            $_SESSION["u_$user_index"]["login_id"] = $username;
            $_SESSION["u_$user_index"]["username"] = $username;
            Utils::appendJSONResponse("username", $username, $success, "✔ Username updated and saved.", false);
          }
            
          // check if email updated
          if (isset($_POST["email"]) && profile_updated("_email", $email, $user_id))
          {
            $_SESSION["u_$user_index"]["login_id"] = $email;
            $_SESSION["u_$user_index"]["email"] = $email;
            Utils::appendJSONResponse("email", $email, $success, "✔ Email updated and saved.", false);
          }
            
          // check if phone updated
          if (isset($_POST["phone"]) && profile_updated("_phone", $phone, $user_id))
          {
            $phone = $_SESSION["u_$user_index"]["phone"] = $phone ? $phone : "―";
            Utils::appendJSONResponse("phone", $phone, $success, "✔ Phone updated and saved.", false);
          }
            
          // check if DOB updated
          if (isset($_POST["DOB"]) && profile_updated("_dob", $DOB, $user_id))
          {
            $_DOB = $DOB ? explode("-", $DOB) : "―";
            $_DOB = $DOB ? "$_DOB[2]-$_DOB[1]-$_DOB[0]" : "―"; //reformatted for display in client side
            $DOB_formatted = $_SESSION["u_$user_index"]["DOB"] = $DOB ? Utils::formatDate($DOB)->formatted : "―";
            $_SESSION["u_$user_index"]["unformatted-DOB"] = $_DOB;
            Utils::appendJSONResponse("DOB", $DOB_formatted, $success, "✔ Date of birth updated and saved.", false);
            Utils::appendJSONResponse("unformatted-DOB", $_DOB, $success, "", false);
          }
            
          // check if password updated
          if (isset($_POST["change-password"]) && profile_updated("_password", $changePassword, $user_id))
            Utils::appendJSONResponse("change-password", "", $success, "✔ Password updated and saved.", false);
        }
        else Utils::appendJSONResponse("current-password", $user_id, $error, "✘ Sorry, couldn't save profile edit because you entered an incorrect current password.", false);
      }
      else if (!isset($_SESSION["u_$user_index"]))
        Utils::appendJSONResponse("current-password", '', $error, "✘ Sorry, couldn't save profile edit because either your account no longer exists or you are not signed in.", false);
      
      $JSON_responses = (object) $JSON_responses; //convert $JSON_responses array to object before sending to client side
      echo json_encode($JSON_responses); //send JSON stringified object of response objects
    }
  }


  $mysql->close();
?>