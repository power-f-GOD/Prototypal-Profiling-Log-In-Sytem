
<!-- Do not alter or tamper with classNames. -->

<?php

  session_start();

  require "php/header.php";

  Using::IndexNavLinks();
  
?>


<!-- sign up form -->
<div class="content">
  <!-- form header title -->
  <h4 class="form-header-title"><span class="g-techly-text g-techly-icon">G</span> - Sign Up</h4>
  <form id="signup-form" method="POST" enctype="multipart/form-data">
    <!-- image / avatar -->
    <div id="image-container">
      <div>
        <label for="image-file" id="image-label" title="Upload image">
          <div id="image-upload-guide">
            <div>
              <i id="camera-icon">&#x1F4F7;</i><br />Tap to upload image
            </div>
          </div>
          <div id="image"></div>
          <input type="file" value="" id="image-file" name="image-file" accept="image/jpeg, image/jpg, image/png" />
        </label>
      </div>
    </div>
    <!-- firstname -->
    <label for="firstname">
      <input type="text" id="firstname" class="required input" name="firstname" placeholder="✎ Firstname" title="Your firstname" />
    </label>
    <!-- lastname -->
    <label for="lastname">
      <input type="text" id="lastname" class="required input" name="lastname" placeholder="✎ Lastname" title="Your lastname" />
    </label>
    <!-- username -->
    <label for="username">
      <input type="text" id="username" class="required input" name="username" placeholder="&#128104; Username" title="Your username. No spaces." />
    </label>
    <!-- email -->
    <label for="email">
      <input type="email" id="email" class="required input" name="email" placeholder="✉ E-mail" title="Your e-mail" />
    </label>
    <!-- phone -->
    <label for="phone">
      <input type="tel" id="phone" class="input" name="phone" placeholder="&#128222; Phone" title="Your phone number" />
    </label>
    <!-- DOB -->
    <label for="DOB">
      <input type="text" id="DOB" class="input" name="DOB" placeholder="&#128197; Date of Birth e.g. 31-12-1999" title="Your date of birth" />
    </label>
    <!-- password -->
    <label for="password">
      <input type="password" class="required input" id="password" name="password" placeholder="&#128274; Password" title="Your password" />
    </label>
    <!-- confirm-password -->
    <label for="confirm-password">
      <input type="password" class="required input" id="confirm-password" name="confirm-password" placeholder="&#128274; Confirm Password" disabled="disabled" title="Confirm your password" />
    </label>
    <!-- sign up (submit) -->
    <div id="sign-in-up-wrapper">
      <span class="span-wrapper">
        <button id="submit" class="button" type="submit" name="submit">Sign Up &#11014;</button>
      </span>
      <span class="span-wrapper sub-text" style='color: #888 !important; font-style: italic;'>
        <u>NB:</u> A verification link will be sent to the email you provide to enable you activate your account.
      </span>
      <span class="span-wrapper">
        Already have an account? <a href="javascript:void(0)" id="signup-page-signin-link" class="">Sign in here</a>
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
