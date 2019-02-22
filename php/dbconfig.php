<?php

  //database config file
  $db_host = "localhost";
  $db_username = "root";
  $db_password = "";
  $db_name = '';
  $mysql = new mysqli($db_host, $db_username, $db_password, $db_name);



  //create and use database if not exists
  if (!$mysql->query('USE profiles;'))
  {
    if ($mysql->query('CREATE DATABASE profiles;'))
      $mysql->query('USE profiles;');
    else exit('Error: Could not create database!');
  }


  //create table if not exists
  if (!$mysql->query('SELECT _id FROM _users LIMIT 1;'))
  {
    $mysql->query(
      "CREATE TABLE _users 
      (
        _id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        _firstname VARCHAR(30) NOT NULL,
        _lastname VARCHAR(30) NOT NULL,
        _username VARCHAR(30) NOT NULL,
        _email VARCHAR(50) NOT NULL,
        _phone VARCHAR(15),
        _dob VARCHAR(10),
        _password VARCHAR(255) NOT NULL,
        _image_name VARCHAR(255),
        _signup_date DATETIME,
        _last_modified_date DATETIME,
        _signed_in TINYINT(1) NOT NULL DEFAULT 0,
        _account_active TINYINT(1) NOT NULL DEFAULT 0,
        _hash VARCHAR(255) NOT NULL
      );"
    );
  }
    
?>