"use strict";





function loadSignupPageScript()
{
  //input file image onchange handler
  Q("#image-file").onchange = function()
  {
    if (this.parentNode.parentNode.querySelector(".feedback-wrapper"))
      Utils.hideInputFeedback(this.parentNode);

    if (this.value)
    {
      let imgURL = URL.createObjectURL(this.files[0]);

      //check if selected file is an image. File should end with a valid image file extension name
      if (/(.jpeg|.jpg|.png)$/i.test(this.value))
        setTimeout(() => Q("#image").style.backgroundImage = `url("${imgURL}")`, 350);
      else
        Q("#image").style.backgroundImage = "",
        Utils.displayInputFeedback(this.parentNode, "error", "Selected file not an image. Valid image formats: PNG, JPEG, JPG.");
    }
  };





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
  
  



  //adding listeners to validate inputs by user
  for (let i = 0; i < QAll("input").length; i++)
    QAll("input")[i].addEventListener("input", validate),
    QAll("input")[i].addEventListener("keyup", validate);

  function validate()
  {
    //hide "Sign up successful or failure message" on sign up of another user
    if (Q(".signup-status-feedback-wrapper"))
      Utils.hideInputFeedback(Q("#submit"));

    //prevent validation for image file to avoid errors
    if (this.type == "file") return;

    let caller = this,
        data = new FormData();

    data.append(caller.name, caller.value);

    //append password to data if input caller is "confirm-password" which will be used on the server for comparison and validation
    if (caller.name == "confirm-password")
      data.append(Q("#password").name, Q("#password").value);

    //check if user "password" input length is > 7 in order to enable "confirm-password"
    if (caller.name == "password")
      if (caller.value.length > 7)
        Q("#confirm-password").disabled = false;
      else Q("#confirm-password").disabled = true;
    
    //display "Validating" feedback message before sending user data
    let feedbackText = caller.name == "confirm-password" ? "Confirming your password..." : `Validating your ${caller.name}...`,
        current_loader = loader_sm.replace("type", `${l_arrow}`),
        callerParent = caller.name == "submit" ? Q("#sign-in-up-wrapper") : caller.parentNode;

    if (callerParent.querySelector(".feedback-wrapper"))
      Utils.changeFeedbackType(caller, "processing", `${current_loader} ${feedbackText}`); 
    else
      Utils.displayInputFeedback(caller, "processing", `${current_loader} ${feedbackText}`);

    //Finally POST input data to server for validation (POST() returns a Promise which has two callbacks passed to its "then()" method: resolve and reject)
    Utils.POST(data, "./php/signup_script.php").then
    (
      //POST request resolved
      function(responseText) 
      {
        try 
        {
          let responses = "", response;

          responses = JSON.parse(responseText);
          response = JSON.parse(responses[caller.name]);
        
          if (response.type == "error")
            Utils.changeFeedbackType(caller, "error", response.message);
          else if (response.type == "processing") 
            Utils.changeFeedbackType(caller, "processing", response.message);
          else 
            Utils.changeFeedbackType(caller, "success", response.message);
        }
        catch(e)
        {
          Utils.changeFeedbackType(caller, "error", '⚠ An unexpected error occurred! Please try again later.');
          console.error(responseText);
        }
      },
      //POST request rejected
      function(status_text)
      {
        Utils.changeFeedbackType(caller, "error", `${status_text} Could not validate your ${caller.name}. Review your network settings.`);
      }
    );
    
    //hide feedback element if no error occurred
    caller.onblur = function()
    {
      if (!caller.parentNode.querySelector(".error"))
        setTimeout(() => Utils.hideInputFeedback(caller), 1200);
    };

    //[just to] prevent input box from receiving focus on click of feedback element wrapper 
    if (caller.parentNode.querySelector(".feedback"))
      caller.parentNode.querySelector(".feedback").onclick = e => e.preventDefault();
  }
  
  

  

  //send signup data to server
  Q("#submit").onclick = function(e)
  {
    //hide all feedbacks if there be any
    Utils.hideAllFeedbacks();

    let caller = this,
        formData = new FormData(Q("#signup-form"));
    
    
    formData.append(this.name, "submit"); //append submit button to be used for processing FormData on server
    this.disabled = true;
    e.preventDefault(); //prevent page from reloading on submit button click
    current_loader = loader_sm.replace("type", l_arrow);
    this.innerHTML = `${current_loader} Signing you up...`;

    //Finally POST signup data to server for registration (POST() returns a Promise which has two callbacks passed to its "then()" method: resolve and reject)
    Utils.POST(formData, "./php/signup_script.php").then
    (
      //POST request resolved
      function(responseText)
      {
        setTimeout(() => 
        {console.log(responseText)
          //responseText is a JSON stringified object of objects sent from the server
          let responses = "", response,
              virtual_caller = caller,
              error_occurred = false,  
              signup_success, success_message,
              //'start' and 'end' are for to get JSON part of responseText due to PHPMailer logs to avoid JSON parse error
              start = responseText.indexOf('{'),
              end = responseText.lastIndexOf('}');

          try
          {
            responseText = responseText.slice(start, end + 1);
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
                  Utils.callInputFeedback(virtual_caller, "error", response.message, callerParent, name == 'submit' ? '⚠ Something Went Wrong!' : '');
                else
                  Utils.callInputFeedback(virtual_caller, "processing", response.message, callerParent);

                error_occurred = true;
              }
              
              if (name == "submit" && response.type == "success") 
                signup_success = true, success_message = response.message;
            }
          }
          catch(e)
          {
            Utils.callInputFeedback(Q(`#submit`), "error", 'An unexpected error occurred! Please, try again later.', Q("#sign-in-up-wrapper"), '⚠ Something Went Wrong!');
            console.error(responseText);
          }

          //reset form fields and style on sign up success
          if (!error_occurred && signup_success) 
          {
            setTimeout(() => 
            {
              Utils.displayInputFeedback(caller, "success", success_message);
              Q("#signup-form").reset();
              Q("#image").style.backgroundImage = "";

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
          caller.innerHTML = "Sign Up &#11014;";
        }, 300);
      },
      //POST request rejected
      function(status_text)
      {
        setTimeout(() =>
        {
          Utils.displayInputFeedback(caller, "error", `Something went wrong while trying to sign you up. Kindly review your network settings and ensure you are connected to the internet.`);
          caller.disabled = false;
          caller.innerHTML = "Sign Up &#11014;";
          Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250);
        }, 500);
      }
    );
  };



  

  //Signup page "Sign in here" button click handler
  Q("#signup-page-signin-link").onclick = () => setTimeout(() => Q(".signin-link").click(), 300);
};




