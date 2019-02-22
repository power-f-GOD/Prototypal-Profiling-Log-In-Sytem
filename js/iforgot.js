"use strict";





function loadiForgotPageScript()
{
  //hide inputs feedback if there be any displayed on input of "login-id or password"
  Q("#iforgot-email").addEventListener("input", function() 
  { Utils.hideAllFeedbacks() });

  
  

  
  //handler to sign user in
  Q("#send-link-button").addEventListener("click", function(e) 
  {
    //hide feedbacks if there be any
    Utils.hideAllFeedbacks();

    let loginFormData = new FormData(Q("#iforgot-link-form")),
        sendLinkButton = this;

    //prevent page from reloading on submit button click
    e.preventDefault(); 
    
    if (Q("#iforgot-email").value.trim() == "")
      return Utils.callInputFeedback(Q("#iforgot-email"), "processing", "⚠ Kindly enter your email.", Q("#iforgot-email").parentNode);

    loginFormData.append(this.name, "send-link-button");
    current_loader = loader_sm.replace("type", l_arrow);
    sendLinkButton.innerHTML = `${current_loader} Sending link...`;
    sendLinkButton.disabled = true;
    
    //Finally POST signin data (POST() returns a Promise which has two callbacks passed to its "then()" method: resolve and reject)
    Utils.POST(loginFormData, "./php/iforgot_script.php").then
    (
      //POST request resolved
      function(responseText)
      {
        //responseText is a JSON stringified object of objects sent from the server
        setTimeout(() => 
        {
          let start = responseText.indexOf('{'),
              end = responseText.lastIndexOf('}'),
              responses, response;

          responseText = responseText.slice(start, end + 1);

          try 
          {
            responses = JSON.parse(responseText);
          
            for (let prop in responses)
            {
              response = JSON.parse(responses[prop]);

              if (response.name == "send-link-button")
              {
                let feedbackTitle = response.type == "success" ? "✔ Confirmation Link sent!" : "✘ Could not send Link!";
                Utils.showInputFeedback(Q(`#${response.name}`), response.type, response.message, "", Q("#sign-in-up-wrapper"), feedbackTitle);

                if (response.type == "success") Q("#iforgot-link-form").reset();
              }
              else if (response.type == "error")
                Utils.showInputFeedback(Q(`#${response.name}`), response.type, response.message);
            }
          }
          catch(e)
          {
            Utils.showInputFeedback(Q(`#send-link-button`), 'error', 'Could not send link. Something went wrong. Please, try again later.', "", Q("#sign-in-up-wrapper"), '⚠ An Unexpected Error Occurred!');
            console.error(responseText);
          }
          
          //scroll form to first input that has feedback type "error" or "warning" that needs to be attended to, filled or filled correctly
          if (Q(".error"))
            Utils.scroll_page_to(Q("body"), Q(".error").offsetTop - 250); 
          else if (Q("processing"))
            Utils.scroll_page_to(Q("body"), Q(".processing").offsetTop - 250);
          else if (Q(".success"))
            Utils.scroll_page_to(Q("body"), Q(".success").offsetTop - 250);

          sendLinkButton.innerHTML = "Send Link &#10152";
          sendLinkButton.disabled = false;
        }, 500);
      },
      //POST request rejected
      function(xhttp)
      {
        setTimeout(() =>
        {
          Utils.showInputFeedback(sendLinkButton, "error", `Something went wrong while trying to send the Confirmation Link to your email. Kindly review your network settings and ensure you are connected to the internet.`,  "", Q("#sign-in-up-wrapper"), "✘ Network Error!");
          sendLinkButton.disabled = false;
          sendLinkButton.innerHTML = "Send Link &#10152;";
          Utils.scroll_page_to(Q("body"), Q(".error").offsetTop - 250);
        }, 500);
      }
    );
  });
}