
  <!DOCTYPE html>
  <!-- Do not alter or tamper with classNames. -->
  <html>
    <head>
      <title>Get Started - G-TECHLY</title>
      <meta charset='utf-8' />
      <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0' />
      <meta id='meta-theme-color' name='theme-color' content='#002b50' />
      <link rel="icon" type="image/png" href="gtechly.png" />
      <!--link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans|Ubuntu|Damion|Audiowide"-->
      <!-- <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' /> -->
      <link rel='stylesheet' type='text/css' href='bootstrap/bootstrap.min.css' />
      <link rel='stylesheet' type='text/css' href='css/main.css' />
      <link rel='stylesheet' type='text/css' href='css/sign_in_up.css' />
      <link rel='stylesheet' type='text/css' href='css/profile.css' />
      <link rel='stylesheet' type='text/css' href='css/users.css' />
      <link rel='stylesheet' type='text/css' href='css/responsive.css' />
      <link rel='stylesheet' type='text/css' href='' id='theme-color' />
    </head>
    <body>
    <div id="not-supported">
        <div id="not-supported-message">
          <h4 class='form-header-title'><span class='g-techly-text g-techly-icon'>G-TECHLY</span> - ⚠ Not Supported</h4>
          Oops! Sorry, your browser is outdated and not supported by this app.<br /> Please, update your browser or switch to a supported browser. <br /><br />Thanks.<br /><br /><br />- <i><b>G-TECHLY Team</b></i>
        </div>
      </div>
      <style type="text/css">
        #not-supported 
        {
          display: table;
          width: 102%;
          height: 102%;
          background: #222;
          position: fixed;
          top: 0;
          left: 0;
          z-index: 20;
        }
        
        #not-supported > div
        {
          display: table-cell;
          text-align: center;
          vertical-align: middle;
          height: 100%;
          width: 100%;
          padding: 8%;
          color: #888;
          line-height: 150%;
        }
      </style>
      <script type="text/javascript">
        //check using 'window.fetch' if browser supports ES6 features
        if (window.fetch)
          document.getElementById("not-supported").style.display = "none";
      </script>
      <!-- nav menu -->
      <header class='fixed-top'>
        <nav class='d-flex justify-content-center align-items-center navbar p-0 hide'>
          <!-- wrapper for icon and toggler button -->
          <div class='icon-toggler-wrapper w-100'>
            <h2 class='g-techly-text g-techly-icon header-icon'>G-TECHLY</h2>
            <button class='navbar-toggler collapsed' type='button' data-toggle='collapse' data-target='#collapsibleNavbar' aria-expanded='false'>
              <span class='nav-toggler-strokes-wrapper'>
                <span class='nav-toggler-strokes'></span>
                <span class='nav-toggler-strokes'></span>
                <span class='nav-toggler-strokes'></span>
              </span>
            </button>
          </div>
        </nav>


<?php

  $profile_nav_links = "
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
            <!-- users page link -->
            <a class='nav-link js--nav-link users-link' href='javascript:void(0)' data-href='ajax_pages/users_content.php'>View G-TECHLY Users</a>
          </li>
          <li class='nav-item'>
            <!-- js-clock link -->
            <a class='nav-link' href='js-clock/clock.html' target='_blank'>See '<b>JS Clock + Alarm</b>'</a>
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
    
    $index_nav_links = "
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
            <li class='nav-item'>
              <!-- js-clock link -->
              <a class='nav-link' href='js-clock/clock.html' target='_blank'>See '<b>JS Clock + Alarm</b>'</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
  <!-- main content -->
  <main id='custom-container' class='hide'>";
    
    
  

  class Using
  {
    public static function ProfileNavLinks()
    {
      global $profile_nav_links; 
      echo $profile_nav_links;
    }

    public static function IndexNavLinks()
    {
      global $index_nav_links; 
      echo $index_nav_links;
    }
  }

?>