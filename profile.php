<?php

  require 'php/dbconfig.php';

  session_start();

  require 'php/utilities.php';
  
  
  if (isset($_GET['user']))
    $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
  else $user_index = 0;


  //if not signed in, redirect to index (Get Started) page
  if (!array_key_exists("u_$user_index", $_SESSION))
  {
    //using initial_requested_page stores/sets actual requested page url; user will then be redirected to sign in page if they are not signed in; then later redirected to actual page requested if finally signed in 
    $_SESSION['url']['initial_requested_page'] = 'profile';
    header('Location: signin?user=0');
    exit;
  }


  //redirect to initially requested page when user wasn't signed in after sign in
  if (array_key_exists('url', $_SESSION))
  {
    if (isset($_GET['u_id']))
    {
      $u_id = Utils::sanitize($mysql->escape_string($_GET['u_id']));
      header('Location: ' . $_SESSION['url']['initial_requested_page'] . '?user=' . $user_index . '&u_id=' . $u_id);
      unset($_SESSION['url']); //uset to avoid a redirect loop
      exit;
    }
  }
    

  $id = $_SESSION["u_$user_index"]['id'];


  //redirect to default user ($user_index = 0) profile home page if current $user_index is not signed in 
  if (!array_key_exists('user', $_GET))
  {
    header("Location: profile?user=$user_index&u_id=$id");
    exit;
  }

  
  //redirect to default user ($user_index = 0) if current $user_index is not signed in
  if (!array_key_exists("u_$user_index", $_SESSION))
  {
    header("Location: home?user=0&u_id=$id");
    exit;
  }
    

  require "php/header.php";
  
  Using::ProfileNavLinks();
  

  $image_name = $_SESSION["u_$user_index"]['image-name'];
  $firstname = $_SESSION["u_$user_index"]['firstname'];
  $lastname = $_SESSION["u_$user_index"]['lastname'];
  $username = $_SESSION["u_$user_index"]['username'];
  $email = $_SESSION["u_$user_index"]['email'];
  $phone = $_SESSION["u_$user_index"]['phone'];
  $dob = $_SESSION["u_$user_index"]['DOB'];
  $signup_date = $_SESSION["u_$user_index"]['signup-date'];
  $last_modified_date = $_SESSION["u_$user_index"]['last-modified-date'];
  $account_is_active = $_SESSION["u_$user_index"]['account-active'];
  $hash = $_SESSION["u_$user_index"]['hash'];


  if (!$account_is_active)
    goto account_inactive_content;
  
?>


<div id='profile-content' class='content'>
  <form id='profile-content-sub-wrapper' class='profile-form' enctype='multipart/form-data' method='POST'>
    <h4 class='form-header-title'><span class='g-techly-text g-techly-icon'>G</span> - Profile</h4>
    <div id='profile-image-wrapper'>
      <!-- profile image -->
      <div id='profile-image' class='profile-detail-value-image-name' style='background-image: url(<?php echo $image_name; ?>);'></div>
      <!-- image for edit mode -->
      <div id='profile-edit-image-label-container'>
        <label for='profile-edit-image-file' id='profile-edit-image-label' title='Upload image'>
          <div id='profile-edit-image-upload-guide'>
            <div>
              <i id='profile-edit-camera-icon'>&#x1F4F7;</i>
              <br />Tap to select image
            </div>
          </div>
          <div id='profile-edit-image' style='background-image: url(<?php echo $image_name; ?>);'></div>
          <input type='file' id='profile-edit-image-file' name='profile-edit-image-file' accept='image/jpeg, image/jpg, image/png' />
        </label>
      </div>
    </div>
    <!-- profile inputs container -->
    <div class='profile-details-inputs-container'>
      <!-- for firstname -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Firstname:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-detail-value-firstname'>
            <?php echo $firstname; ?>
          </span>
          <input class='profile-detail-input-firstname' type='text' name='firstname' title='Enter firstname you wish to change to' />
        </div>
      </div>
      <!-- for lastname -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Lastname:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-detail-value-lastname'>
            <?php echo $lastname; ?>
          </span>
          <input class='profile-detail-input-lastname' type='text' name='lastname' title='Enter lastname you wish to change to' />
        </div>
      </div>
      <!-- for username -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Username:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-detail-value-username'>
            <?php echo $username; ?>
          </span>
          <input class='profile-detail-input-username' type='text' name='username' title='Enter username you wish to change to' />
        </div>
      </div>
      <!-- for email -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>E-mail:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-detail-value-email'>
            <?php echo $email; ?>
          </span>
          <input class='profile-detail-input-email' type='email' name='email' title='Enter email you wish to change to' />
        </div>
      </div>
      <!-- for phone -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Phone:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-detail-value-phone'>
            <?php echo $phone; ?>
          </span>
          <input class='profile-detail-input-phone' type='tel' name='phone' title='Enter phone you wish to change to' />
        </div>
      </div>
      <!-- for DOB -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Date of Birth:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-detail-value-DOB'>
            <?php echo $dob; ?>
          </span>
          <input class='profile-detail-input-DOB' type='text' name='DOB' title='Enter DOB you wish to change to' />
        </div>
      </div>
      <!-- for change password -->
      <div class='profile-detail-item-wrapper change-password-wrapper'>
        <div class='profile-detail-item-name'>Wish to change Password?</div>
        <div class='profile-detail-item-value-wrapper'>
          <input class='profile-detail-input-change-password' type='password' name='change-password' placeholder='Enter new password' title='Enter password you wish to change to' />
        </div>
      </div>
      <!-- for confirm changed password -->
      <div class='profile-detail-item-wrapper confirm-changed-password-wrapper'>
        <div class='profile-detail-item-name'>Confirm new Password:</div>
        <div class='profile-detail-item-value-wrapper'>
          <input class='profile-detail-input-confirm-changed-password' type='password' name='confirm-changed-password' placeholder='Confirm new password' disabled title='Confirm your new password' />
        </div>
      </div>
      <!-- for sign up date -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Sign-up Date:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-signup-date'>
            <?php echo $signup_date; ?>
          </span>
        </div>
      </div>
      <!-- for profile last edit date -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name'>Profile last edit Date:</div>
        <div class='profile-detail-item-value-wrapper'>
          <span class='profile-last-modified-date'>
            <?php echo $last_modified_date; ?>
          </span>
        </div>
      </div>
      <!-- current password -->
      <div class='profile-detail-item-wrapper current-password-wrapper'>
        <div class='profile-detail-item-name'>Enter current Password to save profile edit:</div>
        <div class='profile-detail-item-value-wrapper'>
          <input class='profile-detail-input-current-password' type='password' name='current-password' placeholder='Enter your current password' title='Enter current Password to save profile edit' disabled />
        </div>
      </div>
      <!-- for profile buttons -->
      <div class='profile-detail-item-wrapper'>
        <div class='profile-detail-item-name profile-detail-edit-button-wrapper'>
          <button id='profile-detail-edit-button' class='button edit-mode-off' type='reset'>✍ Edit</button>
        </div>
        <div class='profile-detail-item-value-wrapper profile-detail-save-button-wrapper'>
          <button id='profile-detail-save-button' class='button' name='submit' type='submit' disabled='disabled'>✔ Save</button>
        </div>
      </div>
    </div>
  </form>
</div>


<?php

  goto footer;

  account_inactive_content:

?>


<div class='feedback-wrapper signup-status-feedback-wrapper' style='margin: 120px 0 70px 0;'>
  <div class='feedback processing'>
    <h4 class='feedback-title'>⚠ Account Inactive!</h4> 
    <span>Hello, <b><?php echo $firstname; ?></b>. You cannot view your profile at this time because your account has not yet been verified. <br />Log on to your email and click the <b>G-TECHLY account verification link</b> to activate your account. <br />Thanks. <br /><br /><br /><b>― G-TECHLY Team</b></span>
  </div>
</div>


<?php

  footer:

?>


<!-- for delete account button -->
<div class='profile-detail-item-wrapper justify-content-center'>
  <button id='profile-delete-account-button' class='button' name='submit' type='submit'>✘ Delete Account</button>
</div>
<h5 class='footer-text'>
  - By <a href='https://api.whatsapp.com/send?phone=2348105506514' style='color: #02c0fd;'>@Power'f GOD⚡⚡</a> &copy; 2019
</h5>


<?php
    
  require "php/footer.php";
  
?>




