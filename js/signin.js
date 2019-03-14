"use strict";





function loadSigninPageScript()
{
  //hide inputs feedback if there be any displayed on input of "login-id or password"
  Q("#login-id").addEventListener("input", function() 
  { Utils.hideAllFeedbacks(); });

  Q("#login-password").addEventListener("input", function() 
  { Utils.hideAllFeedbacks(); });

  
 


  //handler to sign user in
  Q("#signin-button").addEventListener("click", function(e) 
  {
    Utils.hideAllFeedbacks();

    let loginFormData = new FormData(Q("#login-form")),
        signinButton = this;

    //prevent page from reloading on submit button click
    e.preventDefault(); 
    
    if (Q("#login-id").value.trim() == "") //impede signin attempt if login-id is blank
      return Utils.callInputFeedback(Q("#login-id"), "processing", "⚠ Enter your G-TECHLY username or email.", Q("#login-id").parentNode);
    else if (Q("#login-id").value.trim().length < 3) //block signin attempt if login-id length is < 3
      return Utils.callInputFeedback(Q("#login-id"), "error", "⚠ Invalid username or email.", Q("#login-id").parentNode);
    else if (Q("#login-password").value == "") //block signin attempt if password is blank
      return Utils.callInputFeedback(Q("#login-password"), "processing", "⚠ Enter password.", Q("#login-password").parentNode);

    loginFormData.append(this.name, "signin-button"); //append submit button to be used for processing FormData on server
    current_loader = loader_sm.replace("type", l_arrow);
    signinButton.innerHTML = `${current_loader} Signing you in...`;
    signinButton.disabled = true;

    if (Q('#sign-in-up-wrapper').querySelector('.feedback'))
      Utils.hideInputFeedback(this);
    
    //Finally POST signin data (POST() returns a Promise which has two callbacks passed to its "then()" method: resolve and reject)
    Utils.POST(loginFormData, "./php/signin_script.php").then
    (
      //POST request resolved
      function(responseText)
      {
        //responseText is a JSON stringified object of objects sent from server
        setTimeout(() => 
        {
          try 
          {
            let responses = JSON.parse(responseText), response;
          
            for (let prop in responses)
            {
              response = JSON.parse(responses[prop]);
  
              if (response.name == "signin-button")
              {
                let feedbackTitle = response.type == "success" ? "✔ Sign in success!" : "✘ Sign in failed!";
                Utils.displayInputFeedback(Q(`#${response.name}`), response.type, response.message, "", Q("#sign-in-up-wrapper"), feedbackTitle);
              }
              else if (response.type == "error")
                Utils.displayInputFeedback(Q(`#${response.name}`), response.type, response.message);
            }
            
            //check if user is logged in then set sessionStorage accordingly
            if (responses["signed-in"])
              if (Number(JSON.parse(responses["signed-in"]).value))
              {
                Utils.scrollPageTo(Q("body"), Q(".success").offsetTop - 250);
                setTimeout(() => 
                {
                  let user = JSON.parse(responses['user-index']).value,
                      id = JSON.parse(responses['id']).value;
  
                  // update sessionStorage values
                  for (let prop in responses)
                  {
                    response = JSON.parse(responses[prop]);
  
                    if (prop == 'image-name')
                      localStorage.setItem(`u_${user}`, `{"image-name": "${response.value}"}`);
                    else
                      sessionStorage.setItem(prop, response.value);
                  }

                  window.location = `home?user=${user}&u_id=${id}`;
                }, 1700);
              }
          }
          catch(e)
          {
            Utils.callInputFeedback(signinButton, "error", 'An unexpected error occurred! Please, try again later.', Q("#sign-in-up-wrapper"), '⚠ Something Went Wrong!');
            console.error(responseText);
          }
         
          //scroll page to 'error' or 'warning' feedback
          if (Q(".error"))
            Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250); 
          else if (Q(".processing"))
            Utils.scrollPageTo(Q("body"), Q(".processing").offsetTop - 250);
          
          signinButton.innerHTML = "Sign In &#10152";
          signinButton.disabled = false;
        }, 500);
      },
      //POST request rejected
      function(xhttp)
      {
        setTimeout(() =>
        {
          Utils.displayInputFeedback(signinButton, "error", `Something went wrong while trying to sign you in. Kindly review your network settings and ensure you are connected to the internet.`,  "", Q("#sign-in-up-wrapper"), "✘ Sign in failed!");
          signinButton.disabled = false;
          signinButton.innerHTML = "Sign In &#10152;";
          Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250);
        }, 500);
      }
    );
  });





  //Signin page "Sign up here" button click handler
  Q("#signin-page-signup-link").onclick = () => setTimeout(() => Q(".signup-link").click(), 300);





  //Goto iforgot-password page onclick of "Reset" link
  Q("#reset-password").addEventListener("click", function() { Utils.get_page(this); });
}