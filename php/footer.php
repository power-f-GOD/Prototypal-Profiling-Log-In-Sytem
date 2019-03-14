      
    </main>
    

    <!-- dark bg-overlay -->
    <div id='bg-overlay' class='hide'></div>

    <!-- modal: confirm box -->
    <div class='d-flex justify-content-center align-items-center fixed-top w-100 h-100 hide action-bg-overlay'>
      <div class='d-flex flex-column confirm-box box-shadow'>
        <h4 class='form-header-title'><span class='g-techly-text g-techly-icon'>G-TECHLY</span> - <span class='action-title'></span></h4>
        <span class='action-message'></span>
        <span class='justify-content-end action-buttons-wrapper'>
          <button class='confirm-yes-button' data-action='delete-account'>Yes, please</button>
          <button class='confirm-no-button'>Please, don't</button>
        </span>
      </div>
    </div>

    <!-- for iforgot password page -->
    <style>
      #send-link-button
      { width: 100%; }

      .sub-text
      { color: #13cffe; }
    </style>

    <button class='position-fixed d-flex justify-content-center align-items-center theme-color-changer dark' title='Color theme toggler'>☀</button>
    

    <!-- <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script> -->
    <!-- <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script> -->
    <script type='text/javascript' src='js/jquery.js'></script>
    <script type='text/javascript' src='bootstrap/bootstrap.min.js'></script>
    <script type='text/javascript' src='js/main.js'></script>
    <script type='text/javascript' src='js/index.js'></script>
    <script type='text/javascript' src='js/utilities.js'></script>
    <script type='text/javascript' src='js/profile.js'></script>
    <script type='text/javascript' src='js/home.js'></script>
    <script type='text/javascript' src='js/signin.js'></script>
    <script type='text/javascript' src='js/signup.js'></script>
    <script type='text/javascript' src='js/iforgot.js'></script>
    <script type='text/javascript' src='js/reset.js'></script>
    <script type='text/javascript'>
      if (Q('.signout-link'))
        Q('.signout-link').addEventListener('click', () => 
        {
          let urlParams = new URLSearchParams(window.location.search),
              user = urlParams.get('user'),
              id = urlParams.get('u_id');

          Utils.GET(`./php/signout_script.php?user=${user}&u_id=${id}`).then
          (
            //GET resolved
            function(responseText)
            {
              setTimeout(() => window.location = 'signin', 300);
            },
            //GET rejected
            function(xhttp)
            {
              setTimeout(() => Q("#custom-container").innerHTML = request_status_info.replace("{status}", xhttp["status"]).replace("{status_text}", xhttp["status_text"]), 300);
            }
          );
        });
    </script>


<?php

  // set client side browser sessionStorage variables and values if user is signed in
  if (array_key_exists('user', $_GET))
  {
    $user_index = isset($user_index) ? $user_index : 0;

    if (array_key_exists("u_$user_index", $_SESSION))
    {
      $sess_vars = array("id", "firstname", "lastname", "username", "email", "phone", "DOB", "image-name", "signup-date", "last-modified-date", "signed-in", "account-active", "unformatted-DOB", 'hash', 'initial_requested_page');

      $signed_in = $_SESSION["u_$user_index"]["signed-in"];
      
      echo
      '<script type="text/javascript">';
        foreach ($sess_vars as $key => $var) 
          if (array_key_exists($var, $_SESSION["u_$user_index"]))
          {
            $var_val = $_SESSION["u_$user_index"]["$var"];
            echo "sessionStorage.setItem('$var', '$var_val');\n";
          }

      echo
      "</script>";
    }
  }

?>


  </body>
</html>
