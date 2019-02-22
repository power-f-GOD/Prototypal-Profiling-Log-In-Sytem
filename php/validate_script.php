<?php

  require "utilities.php";


  //validation for image
  if (isset($_FILES["image-file"]) || isset($_FILES["profile-edit-image-file"]))
  {
    $image_POST_name = isset($_FILES["image-file"]) ? "image-file" : "profile-edit-image-file";
    $valid_extensions = array("jpg", "jpeg", "png");
    $image = $_FILES[$image_POST_name];
    $image_path = "avatar_blue.png";
   
    //if no image is selected
    if (!$image["name"])
      Utils::appendJSONResponse($image_POST_name, "", $processing, "⚠ No image file selected.", false);
    else
    {
      $image["name"] = str_replace(" ", "_", strtolower($image["name"]));
      $image_path = $uploads_dir . basename($image["name"]);
      if (!in_array(pathinfo($image_path, PATHINFO_EXTENSION), $valid_extensions))
        Utils::appendJSONResponse($image_POST_name, "", $error, "⚠ Selected file not an image. Valid image formats: JPEG, JPG or PNG.", false);
      //prevent upload of image until form is submitted
      else if (isset($_POST["submit"]))
      {
        //delete image if same exists to avoid duplicates and save space on server
        if (file_exists($image_path))
          unlink($image_path);
      
        if (move_uploaded_file($image["tmp_name"], $image_path))
        {
          $image_path = substr_replace($image_path, "", strpos($image_path, "."), 1); //in order to fix image file path error with client side CSS
          Utils::appendJSONResponse($image_POST_name, "", "", "Image uploaded.", false);
        }
        else
          Utils::appendJSONResponse($image_POST_name, "", $error, "⚠ Couldn't upload image.", false);
      }
    }
  }


  //validation for firstname
  if (isset($_POST["firstname"]))
  {
    $firstname = strtolower($_POST["firstname"]); //lowercase firstname
    if (empty($firstname))
      Utils::appendJSONResponse("firstname", "", $processing, $fieldEmptyMsg, true);
    else if (!preg_match("/^[a-z]+$/", $firstname))
      Utils::appendJSONResponse("firstname", "", $error, "⚠ Input rejected. Only letters allowed.", true);
    else if (strlen($firstname) < 2)
      Utils::appendJSONResponse("firstname", "", $error, "⚠ Input rejected. Enter full firstname.", true);
    else if (strlen($firstname) > 30)
      Utils::appendJSONResponse("firstname", "", $error, "⚠ Input rejected. Firstname too long.", true);
    else
    {
      //Capitalize first letter of firstname
      $firstname = str_split($firstname);
      $firstname[0] = strtoupper($firstname[0]);
      $firstname = implode("", $firstname);
      $firstname = Utils::sanitize($firstname);
      Utils::appendJSONResponse("firstname", $firstname, $success, "✔ Firstname accepted.", true);
    }
  }
  

  //validation for lastname
  if (isset($_POST["lastname"]))
  {
    $lastname = strtolower($_POST["lastname"]); //lowercase lastname
    if (empty($lastname))
      Utils::appendJSONResponse("lastname", "", $processing, $fieldEmptyMsg, true);
    else if (!preg_match("/^[a-z]+$/", $lastname))
      Utils::appendJSONResponse("lastname", "", $error, "⚠ Input rejected. Only letters allowed.", true);
    else if (strlen($lastname) < 2)
      Utils::appendJSONResponse("lastname", "", $error, "⚠ Input rejected. Enter full lastname.", true);
    else if (strlen($lastname) > 30)
      Utils::appendJSONResponse("lastname", "", $error, "⚠ Input rejected. Lastname too long.", true);
    else
    {
      //Capitalize first letter of lastname
      $lastname = str_split($lastname);
      $lastname[0] = strtoupper($lastname[0]);
      $lastname = implode("", $lastname);
      $lastname = Utils::sanitize($lastname);
      Utils::appendJSONResponse("lastname", "", $success, "✔ Lastname accepted.", true);
    }
  }
  

  //validation for username
  if (isset($_POST["username"]))
  {
    $username = strtolower($_POST["username"]); //lowercase username
    if (empty($username))
      Utils::appendJSONResponse("username", "", $processing, $fieldEmptyMsg, true);
    else if (strlen($username) < 3)
      Utils::appendJSONResponse("username", "", $error, "⚠ Username length should not be less than 3.", true);
    else if (strlen($username) > 30)
      Utils::appendJSONResponse("username", "", $error, "⚠ Username too long. Please, shorten.", true);
    else if (!preg_match("/^[a-z0-9_.]+$/", $username))
      Utils::appendJSONResponse("username", "", $error, "⚠ Invalid username. Only alphanumeric characters, dots or underscores allowed.", true);
    else
    {
      $username = Utils::sanitize($username);
      $username_exists = $mysql->query("SELECT * FROM _users WHERE _username = '$username'");
      if ($username_exists->num_rows)
        Utils::appendJSONResponse("username", "", $processing, "⚠ Username already taken. Kindly use another.", true);
      else
        Utils::appendJSONResponse("username", "", $success, "✔ Username accepted.", true);
    }
  }


  //validation for e-mail
  if (isset($_POST["email"]))
  {
    $email = strtolower($_POST["email"]); //lowercase email
    if (empty($email))
      Utils::appendJSONResponse("email", "", $processing, $fieldEmptyMsg, true);
    else if (!preg_match("/^\w+[\w\d.]*[\w\d]+@\w+\.[\w\d.]+[\w\d]$/i", $email))
      Utils::appendJSONResponse("email", "", $error, "⚠ Input not a valid email.", true);
    else if (strlen($email) > 50)
      Utils::appendJSONResponse("email", "", $error, "⚠ Email too long.", true);
    else
    {
      $email = Utils::sanitize($email);
      $email_exists = $mysql->query("SELECT * FROM _users WHERE _email = '$email'");
      if ($email_exists->num_rows)
        Utils::appendJSONResponse("email", "", $processing, "⚠ Sorry, this email already exists. Please, use another.", true);
      else
        Utils::appendJSONResponse("email", "", $success, "✔ Email valid and accepted.", true);
    }
  }

  
  //validation for phone
  if (isset($_POST["phone"]))
  {
    $phone = $_POST["phone"];
    if (empty($phone))
      Utils::appendJSONResponse("phone", "", $processing, "Input phone number.", false);
    else if (!is_numeric($phone) || strlen($phone) < 6 || strlen($phone) > 15 || !preg_match("/^(\+\d{2,4}|0)\d{7,11}$/", $phone))
      Utils::appendJSONResponse("phone", "", $error, "⚠ Input not a valid phone number. Input your phone number.", true);
    else
      Utils::appendJSONResponse("phone", "", $success, "✔ Number valid and accepted.", false);
    $phone = Utils::sanitize($phone);
  }
  

  //validation for DOB
  if (isset($_POST["DOB"]))
  {
    $DOB = $_POST["DOB"];
    if (empty($DOB))
      Utils::appendJSONResponse("DOB", "", $processing, "⚠ Input date of birth.", false);
    else if (!preg_match("/^\d{2,2}\-\d{2,2}\-\d{4,4}$/", $DOB))
      Utils::appendJSONResponse("DOB", "", $error, "⚠ Invalid date format. Acceptable date format: dd-mm-yyyy.", true);
    else
    {
      $DOB = Utils::sanitize($DOB);
      $DOB = explode("-", $DOB);
      if (($DOB[0] > 31 || $DOB[0] < 1) || ($DOB[1] > 12 || $DOB[1] < 1))
        Utils::appendJSONResponse("DOB", "", $error, "⚠ Invalid date.", true);
      else if ($DOB[2] > 2014 || $DOB[2] < 1900)
        Utils::appendJSONResponse("DOB", "", $error, "⚠ You, certainly, were not born on this date.", true);
      else {
        $DOB = "$DOB[2]-$DOB[1]-$DOB[0]";
        Utils::appendJSONResponse("DOB", "", "", "✔ Date of Birth valid and accepted.", false);
      }
    }
  }
  

  //validation for password for both signup and profile edit
  $changed_password_accepted = false;
  if (isset($_POST["password"]) || isset($_POST["change-password"]))
  {
    $password = isset($_POST["password"]) ? $_POST["password"] : $_POST["change-password"];
    $password_name = isset($_POST["password"]) ? "password" : "change-password";
    if (empty($password))
      Utils::appendJSONResponse($password_name, "", $processing, $fieldEmptyMsg, true);
    else if (strlen($password) < 8)
      Utils::appendJSONResponse($password_name, "", $error, "⚠ Password length must not be less than 8.", true);
    else if (strlen($password) > 255)
      Utils::appendJSONResponse($password_name, "", $error, "⚠ Password too long.", true);
    else if (!preg_match("/(\d{1,}|\W{1,})/", $password))
      Utils::appendJSONResponse($password_name, "", $processing, "⚠ Password weak. Consider combining with non-alphabetic characters.", true);
    else
    {
      $changed_password_accepted = true;
      Utils::appendJSONResponse($password_name, "", "", "✔ Password accepted.", true);
    } 
  }
  

  //validation for confirm-password
  if (isset($_POST["password"]) && isset($_POST["confirm-password"])) 
  {
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];
    if (empty($confirmPassword))
      Utils::appendJSONResponse("confirm-password", "", $processing, $fieldEmptyMsg, true);
    else if ($confirmPassword != $password)
      Utils::appendJSONResponse("confirm-password", "", $error, "⚠ Passwords don't match.", true);
    else
      Utils::appendJSONResponse("confirm-password", "", "", "✔ Passwords match. Password confirmed.", true);
  }


  //validation for confirm-changed-password for profile edit
  if (isset($_POST["change-password"]) && isset($_POST["confirm-changed-password"])) 
  {
    $changePassword = $_POST["change-password"];
    $confirmChangedPassword = $_POST["confirm-changed-password"];
    if (empty($confirmChangedPassword))
      Utils::appendJSONResponse("confirm-changed-password", "", $processing, $fieldEmptyMsg, true);
    else if ($confirmChangedPassword != $changePassword)
      Utils::appendJSONResponse("confirm-changed-password", "", $error, "⚠ Passwords don't match.", true);
    else if ($changed_password_accepted)
      Utils::appendJSONResponse("confirm-changed-password", "", "", "✔ Passwords match. Password confirmed.", true);
  }
?>