
<!-- Do not alter or tamper with classNames. Add your own custom classNames instead. -->

<?php

  session_start();

  
  if (!isset($_GET['email']) || !isset($_GET['hash']))
    header("Location: http://localhost/g-techly/signin?user=0");
    

  require "php/header.php";

  Using::IndexHeader();
  
?>


<!-- sign up form -->
<div class="content">
  <!-- form header title -->
  <h5 class="form-header-title"><span class="g-techly-text g-techly-icon">G-TECHLY</span> - Reset Password</h5>
  <form id="reset-password-form" method="POST" enctype="multipart/form-data">
    <!-- new password -->
    <label for="password">
      <input type="password" class="required input" id="password" name="password" placeholder="&#128274; Enter new password" title="Your new password" />
    </label>
    <!-- confirm new password -->
    <label for="confirm-password">
      <input type="password" class="required input" id="confirm-password" name="confirm-password" placeholder="&#128274; Confirm new password" disabled="disabled" title="Confirm your new password" />
    </label>
    <!-- reset (submit) -->
    <div id="sign-in-up-wrapper">
      <span class="span-wrapper">
        <button id="submit" class="button" type="submit" name="submit">Reset Password ↻</button>
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
