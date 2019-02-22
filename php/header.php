<?php

  $header_head = "
  <!DOCTYPE html>
  <!-- Do not alter or tamper with classNames. I repeat. Do not alter or tamper with classNames. Code would break and become a nightmare if you do, because classNames are used everywhere both on the server and on the client. Add your own custom classNames instead. Peace. -->
  <html>
    <head>
      <title>Get Started - G-TECHLY</title>
      <meta charset='utf-8' />
      <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
      <meta id='metallic' name='theme-color' content='#282828' />
      <!-- <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' /> -->
      <link rel='stylesheet' type='text/css' href='bootstrap/bootstrap.min.css' />
      <link rel='stylesheet' type='text/css' href='css/main.css' />
      <link rel='stylesheet' type='text/css' href='css/sign_in_up.css' />
      <link rel='stylesheet' type='text/css' href='css/profile.css' />
      <link rel='stylesheet' type='text/css' href='' id='theme-color' />
    </head>
    <body>
      <!-- nav menu -->
      <header class='fixed-top'>
        <nav class='navbar p-0 hide'>
          <!-- wrapper for icon and toggler button -->
          <div class='icon-toggler-wrapper w-100'>
            <h2 class='g-techly-text g-techly-icon header-icon'>G-TECHLY</h2>
            <button class='navbar-toggler collapsed' type='button' data-toggle='collapse' data-target='#collapsibleNavbar' aria-expanded='false'>
              <span class='navbar-toggler-icon'>☰</span>
            </button>
          </div>
        </nav>";
    
    $profile_links = "
        <!-- nav links -->
        <div class='navbar-collapse collapse' id='collapsibleNavbar'>
          <ul class='navbar-nav'>
            <li class='nav-item'>
              <!-- home page link -->
              <a class='nav-link js--nav-link home-link' href='javascript:void(0)' data-href='ajax_pages/home_content.php'>Home</a>
            </li>
            <li class='nav-item'>
              <!-- profile page link -->
              <a class='nav-link js--nav-link profile-link' href='javascript:void(0)' data-href='ajax_pages/profile_content.php'>My Profile</a>
            </li>
            <li class='nav-item'>
              <!-- signout page link -->
              <a class='nav-link signout-link' href='javascript:void(0)'>Sign Out</a>
            </li>
          </ul>
        </div>
      </header>
      <style>
        .navbar-toggler
        { border: 1px solid lightgrey; }
    
        .navbar-toggler:hover
        {
          border-color: rgb(65, 171, 223);
          transform: scale(1.075);
        }
      </style>
      <!-- main content -->
      <main id='custom-container' class='hide'>";
      
      $index_links = "
          <!-- nav links -->
          <div class='navbar-collapse collapse' id='collapsibleNavbar'>
            <ul class='navbar-nav'>
              <li class='nav-item'>
                <!-- home page link -->
                <a class='nav-link js--nav-link home-link' href='javascript:void(0)' data-href='ajax_pages/index_content.php'>G-TECHLY Home</a>
              </li>
              <li class='nav-item'>
                <!-- signin page link -->
                <a class='nav-link js--nav-link signin-link' href='javascript:void(0)' data-href='ajax_pages/signin_content.php'>Sign In</a>
              </li>
              <li class='nav-item'>
                <!-- signup page link -->
                <a class='nav-link js--nav-link signup-link' href='javascript:void(0)' data-href='ajax_pages/signup_content.php'>Sign Up</a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
    <!-- main content -->
    <main id='custom-container' class='hide'>";
    
    
  
  class Using
  {
    public static function ProfileHeader()
    {
      global $header_head, $profile_links; 
      echo $header_head . $profile_links;
    }

    public static function IndexHeader()
    {
      global $header_head, $index_links; 
      echo $header_head . $index_links;
    }
  }
?>