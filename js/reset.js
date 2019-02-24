"use strict";





function loadResetPageScript()
{


  //adding listener to check if required fields are filled and adjust input red left-border accordingly
  for (let i = 0; i < QAll(".required").length; i++)
    QAll(".required")[i].addEventListener('input', checkIfFilled);

  function checkIfFilled()
  {
    if (this.value.trim())
      this.style.borderLeft = "0 solid red";
    else
      this.style.borderLeft = "5px solid red";
  }
  
  



  //add listeners to validate inputs by user
  for (let i = 0; i < QAll("input").length; i++)
    QAll("input")[i].addEventListener("input", validate),
    QAll("input")[i].addEventListener("keyup", validate);

  function validate()
  {
    let caller = this;
    
    Utils.hideAllFeedbacks();

    if (caller.name == "password")
    {
      if (caller.value && caller.value.trim().length > 7)
        Q("#confirm-password").disabled = false;
      else Q("#confirm-password").disabled = true;
    }
  }
  
  

  

  //send signup data to server
  Q("#submit").onclick = function(e)
  {
    let urlParams = new URLSearchParams(window.location.search),
        EMAIL = urlParams.get('email'),
        HASH = urlParams.get('hash'),
        caller = this,
        formData = new FormData(Q("#reset-password-form"));

    Utils.hideAllFeedbacks();
    formData.append(caller.name, "submit"); //append submit button to be used for processing FormData on server
    caller.disabled = true;
    e.preventDefault(); //prevent page from reloading on submit button click
    current_loader = loader_sm.replace("type", l_arrow);
    caller.innerHTML = `${current_loader} Resetting your password...`;

    //Finally POST signup data to server for registration (POST() returns a Promise which has two callbacks passed to its "then()" method: resolve and reject)
    Utils.POST(formData, `./php/reset_script.php?email=${EMAIL}&hash=${HASH}`).then
    (
      //POST request resolved
      function(responseText)
      {
        setTimeout(() => 
        {
          //responseText is a JSON stringified object of objects sent from the server
          let responses = "", response,
              virtual_caller = caller,
              error_occurred = false,  
              reset_success, success_message;
          
          try 
          {
            responses = JSON.parse(responseText);
          
            for (let name in responses)
            {
              response = JSON.parse(responses[name]);

              //if an error occurred or if there is a warning message and input field is required, do these
              if (Q(`#${response.name}`) && (response.type == "error" || (response.type == "processing" && response.field_required)))
              {
                virtual_caller = Q(`#${response.name}`);
                
                let callerParent = name == "submit" ? Q("#sign-in-up-wrapper") : (virtual_caller.type == 'file' ? virtual_caller.parentNode.parentNode : virtual_caller.parentNode);
                
                if (response.type == "error")
                  Utils.callInputFeedback(virtual_caller, "error", response.message, callerParent, '⚠ Error!');
                else
                  Utils.callInputFeedback(virtual_caller, "processing", response.message, callerParent);

                error_occurred = true;
              }
              
              if (name == "submit" && response.type == "success") 
                reset_success = true, success_message = response.message;
            }
          }
          catch(e)
          {
            Utils.callInputFeedback(Q(`#submit`), "error", 'An unexpected error occurred! Please, try again later.', Q("#sign-in-up-wrapper"), '⚠ Something Went Wrong!');
            console.error(responseText);
          }
          

          //reset form fields and style on sign up success
          if (!error_occurred && reset_success) 
          {
            setTimeout(() => 
            {
              Utils.displayInputFeedback(caller, "success", success_message, '', '', '✔ Password Reset!');
              Q("#reset-password-form").reset();

              for (let i = 0; i < QAll(".required").length; i++)
                QAll(".required")[i].style.borderLeft = "5px solid red";

              Q("#confirm-password").disabled = true;
              Utils.scrollPageTo(Q("body"), Q(".signup-status-feedback-wrapper").offsetTop - 250);
            }, 350);
          }
          //scroll form to first input that has feedback type "error" or "warning" that should be attended to, filled or filled correctly
          else if (Q(".error"))
            Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250); 
          else if (Q(".processing"))
            Utils.scrollPageTo(Q("body"), Q(".processing").offsetTop - 250);
        
          caller.disabled = false;
          caller.innerHTML = "Reset Password ↻";
          setTimeout(() =>
          {
            if (Q(".reset-signin-link"))
              Q(".reset-signin-link").addEventListener("click", function()
              { Utils.get_page(this); });
          }, 400)
        }, 300);
      },
      //POST request rejected
      function(status_text)
      {
        setTimeout(() =>
        {
          Utils.displayInputFeedback(caller, "error", `Something went wrong while trying to reset your password. Kindly review your network settings and ensure you are connected to the internet then try resetting your password again.`);
          caller.disabled = false;
          caller.innerHTML = "Reset Password ↻";
          Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250);
        }, 500);
      }
    );
  };
};




