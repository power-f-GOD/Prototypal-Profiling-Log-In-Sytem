      
    </main>

    

    <!-- dark bg-overlay -->
    <div id='bg-overlay' class='hide'></div>

    <div class='d-flex justify-content-center align-items-center fixed-top w-100 h-100 text-center hide user-not-signed-in-bg-overlay'>
      <div class='feedback-wrapper signup-status-feedback-wrapper box-shadow'>
        <div class='feedback user-signed-in-check'>
          <h4 class='feedback-title'>⚠ Not Signed In</h4> 
          <span>Sorry, you are no longer signed in. <br />Please, <a href='signin?user=0' style='color: blue;'>sign in</a> to continue.</span>
        </div>
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
    <!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script> -->
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

          sessionStorage.clear()

          Utils.GET(`./php/signout_script.php?user=${user}&u_id=${id}`).then
          (
            //GET resolved
            function(responseText)
            {
              setTimeout(() => window.location = 'http://localhost/g-techly/signin', 300);
            },
            //GET rejected
            function(xhttp)
            {
              setTimeout(() => Q("#custom-container").innerHTML = request_status_info.replace("{status}", xhttp["status"]).replace("{status_text}", xhttp["status_text"]), 300);
            }
          );
        });
    </script>
  </body>
</html>
