<?php

  require '../php/dbconfig.php';

  session_start();

  require '../php/utilities.php';
  

  $user_index = Utils::sanitize($mysql->escape_string($_GET['user']));
  

  if (!isset($_SESSION["u_$user_index"]))
  {
    echo "<h2>Please, <a href='signin?user=0'>sign in</a> to view users.</h2>";;
    exit;
  }
  

  $firstname = $_SESSION["u_$user_index"]['firstname'];
  $account_is_active = $_SESSION["u_$user_index"]['account-active'];


  $users_content_open = "
    <div class='content users-content'>
      <div class='users-content-sub-wrapper'>
        <h4 class='form-header-title'>
          <span class='g-techly-text g-techly-icon'>G</span> - Users
        </h4>
        <hr class='users-section-separator' />";

  $users_content_close = "
      </div>
    </div>";


  $account_inactive_content = "
    <div class='feedback-wrapper signup-status-feedback-wrapper'>
      <div class='feedback processing'>
        <h4 class='feedback-title'>⚠ Account Inactive!</h4> 
        <span>Hello, <b>$firstname</b>. You account is inactive. <br />To continue, please, log on to your email and click the <b>G-TECHLY account verification link</b> to activate your account. <br />Thanks. <br /><br /><br /><b>― G-TECHLY Team</b></span>
      </div>
    </div>";

  
  $footer_text = "
    <br />
    <h5 class='footer-text'>
      - By <a href='https://api.whatsapp.com/send?phone=2348105506514' style='color: #02c0fd;'>@Power'f GOD⚡⚡</a> &copy; 2019
    </h5>";

  

  if ($account_is_active)
  {
    $users = $mysql->query("SELECT * FROM _users;");
    $num_users = $users->num_rows;

    echo preg_replace("/- Users/", "- Users ($num_users)", $users_content_open, 1);

    for ($i = 0; $i < $num_users; $i++)
    {
      $users_data = $users->fetch_assoc();
      $image_name = $users_data['_image_name'];
      $fullname = $users_data['_firstname'] . ' ' . $users_data['_lastname'];
      $username = $users_data['_username'];
      $email = $users_data['_email'];
      $signup_date = Utils::formatDate($users_data['_signup_date'])->formatted;

      echo "
        <section class='d-flex flex-wrap user-container'>
          <div class='user-image-wrapper'>
            <div class='user-image' style='background-image:url($image_name)'></div>
          </div>
          <div class='d-flex flex-column justify-content-around user-info-wrapper'>
            <div>
              <h5 class='user-info-name'>$fullname</h5>
              <span class='user-info-username'>@$username | $email</span>
            </div>
            <span class='user-info-joined'>Joined: $signup_date</span>
          </div>
        </section>
        <hr class='users-section-separator' />";
    }

    echo $users_content_close . $footer_text;
  }
  else
    echo $account_inactive_content . $footer_text;
  
?>




