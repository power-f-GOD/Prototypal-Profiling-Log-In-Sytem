
<!-- Do not alter or tamper with classNames. Add your own custom classNames instead. -->

<?php

  require 'php/dbconfig.php';

  session_start();


  //using 'user' URL parameter to allow multiple users have access to sign in page in case a user is already signed in
  if (!array_key_exists('user', $_GET))
    if (array_key_exists('u_0', $_SESSION))
    {
      $id = $_SESSION["u_0"]['id'];
      header("Location: http://localhost/g-techly/profile?user=0&u_id=$id");
    }
 

  require "php/header.php";

  Using::IndexHeader();

?>


<div class="content">
  <!-- form header title -->
  <h5 class="form-header-title"><span class="g-techly-text g-techly-icon">G-TECHLY</span> - iForgot Password</h5>
  <form id="iforgot-link-form" method="POST" enctype="multipart/form-data">
    <!-- email -->
    <label for="link-email">
      <input type="email" id="iforgot-email" class="input" name="iforgot-email" placeholder="✉ Enter your E-mail" title="Enter your G-TECHLY E-mail" />
    </label>
    <!-- send link (submit) -->
    <div id="sign-in-up-wrapper">
      <span class="span-wrapper">
        <button id="send-link-button" class="button" type="submit" name="send-link-button">Send Link ➤</button>
      </span>
      <span class="span-wrapper sub-text">
        <i>A confirmation link will be sent to your email.</i>
      </span>
    </div>
  </form>
</div>
<br />
<h5 class='footer-text'>
  - By <a href='https://api.whatsapp.com/send?phone=2348105506514' style='color: #02c0fd;'>@Power'f GOD⚡⚡</a> &copy; 2019
</h5>


<?php
  require "php/footer.php";
?>
