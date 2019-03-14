
<?php
  
  session_start();

?>


<div class="content">
  <!-- form header title -->
  <h4 class="form-header-title"><span class="g-techly-text g-techly-icon">G</span> - Sign In</h4>
  <form id="login-form" method="POST" enctype="multipart/form-data">
    <!-- email -->
    <label for="login-name">
      <input type="email" id="login-id" class="input" name="login-id" placeholder="✉ E-mail or Username" title="Enter your e-mail or username" />
    </label>
    <!-- password -->
    <label for="login-password">
      <input type="password" class="input" id="login-password" name="login-password" placeholder="&#128274; Password" title="Enter your password" />
    </label>
    <!-- sign up (submit) -->
    <div id="sign-in-up-wrapper">
      <span class="span-wrapper">Forgot password? <a href="javascript:void(0)" id="reset-password" class="js--nav-link" data-href='ajax_pages/iforgot_content.php'>Reset</a></span>
      <span class="span-wrapper"><button id="signin-button" class="button" type="submit" name="signin-button">Sign In &#10152;</button></span>
      <span class="span-wrapper">Don't have an account? <a href="javascript:void(0)" id="signin-page-signup-link" class="">Sign up here</a></span>
    </div>
  </form>
</div>

<br />
<h5 class='footer-text'>
  - By <a href='https://api.whatsapp.com/send?phone=2348105506514' style='color: #02c0fd;'>@Power'f GOD⚡⚡</a> &copy; 2019
</h5>
