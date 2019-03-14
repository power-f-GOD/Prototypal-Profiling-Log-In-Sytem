
"use strict";





function loadProfilePageScript()
{
  //a somewhat measure to check if user is signed in or not. If signed in run script else exit
  if (!Q('#profile-delete-account-button'))
    return;





  Q('#profile-delete-account-button').onclick = function()
  {
    let delButton = this,
        current_loader = loader_sm.replace("type", l_arrow);
    
    Utils.displayModal(
    {
      title: '⚠ Delete Account',
      message: 'Are you sure you want to permanently delete your account?',
      buttons: true,
      takeAction: () =>
      {
        delButton.disabled = true;
        delButton.innerHTML = `${current_loader} Deleting account...`;
        Utils.GET(`./php/delete_account.php?user=${USER}&u_id=${ID}`).then
        (
          function(responseText) 
          {
            let response = JSON.parse(responseText);

            setTimeout(() => 
            {
              Utils.displayModal(
              {
                title: JSON.parse(response['delete-account']).value,
                message: JSON.parse(response['delete-account']).message,
                buttons: false,
              });
              delButton.disabled = false;
              delButton.innerHTML = '✘ Delete Account';
            }, 300);
          },
          function(xhttp)
          {
            setTimeout(() => 
            {
              Utils.displayModal(
              {
                title: xhttp['status'],
                message: xhttp['status_text'],
                buttons: false,
              });
              delButton.disabled = false;
              delButton.innerHTML = '✘ Delete Account';
            }, 300);
          }
        );
      }
    });
  }





  //if account is not yet active (verified) prevent function execution to avoid 'null' errors
  if (sessionStorage.getItem('account-active') != 1)
    return;



  let allProfileInputs = QAll("input[class*='profile-detail-input']"),
      allProfileValues = QAll("span[class*='profile-detail-value']"),
      changePassword = Q(".profile-detail-input-change-password"),
      confirmChangedPassword = Q(".profile-detail-input-confirm-changed-password"),
      currentPassword = Q(".profile-detail-input-current-password"),
      saveButton = Q("#profile-detail-save-button");





  //input file image onchange handler
  Q("#profile-edit-image-file").onchange = function()
  {
    if (!currentPassword.value)
      currentPassword.parentNode.style.background = '#700';

    Utils.hideAllFeedbacks();

    if (this.parentNode.parentNode.querySelector(".feedback-wrapper"))
      Utils.hideInputFeedback(this.parentNode);

    if (this.value)
    {
      let imgURL = URL.createObjectURL(this.files[0]);

      //check if selected file is an image with a valid image file extension name
      if (/(.jpeg|.jpg|.png)$/i.test(this.value))
        enable_current_password(),
        setTimeout(() => Q("#profile-edit-image").style.backgroundImage = `url("${imgURL}")`, 350);
      else
        Q("#profile-edit-image").style.backgroundImage = "",
        Utils.displayInputFeedback(this.parentNode, "error", "Selected file not an image. Acceptable image formats: PNG, JPEG, JPG.");
    }
  };





  //enable save button if user has input any value or made any change to any profile-edit input
  for (let i = 0; i < allProfileInputs.length; i++)
  {
    allProfileInputs[i].addEventListener("input", function()
    {
      enable_current_password();
      Utils.hideAllFeedbacks();

      if (!currentPassword.value)
        currentPassword.parentNode.style.background = '#700';
    });

    //add event listener for when "Enter" key is pressed: Triggers "Save" function
    allProfileInputs[i].addEventListener("keyup", function(e) 
    {
      let _event = event || e;
      
      if (_event.which == 13 || _event.keyCode == 13)
      {
        if (currentPassword.disabled)
          Q("#profile-detail-edit-button").click();
        else if (!currentPassword.value || currentPassword.value.length < 8)
        {
          Utils.callInputFeedback(currentPassword.parentNode, 'processing', '⚠ Enter your current password to save changes.', '', '');
          Utils.scrollPageTo(Q("body"), Q(".processing").offsetTop - 250);
          setTimeout(() => currentPassword.focus(), 350);
        }
      }
      else allow_save();
    });
  }





  //switch between edit mode and profile mode
  Q("#profile-detail-edit-button").addEventListener("click", function(e)
  {
    e.preventDefault();

    if (/edit-mode-on/.test(this.className))
    {
      this.classList.add("edit-mode-off");
      this.classList.remove("edit-mode-on");
      this.innerHTML = "✍ Edit";
      turnOffProfileEditMode();
    }
    else 
    {
      this.classList.add("edit-mode-on");
      this.classList.remove("edit-mode-off");
      this.innerHTML = "☒ Dismiss";
      turnOnProfileEditMode();
    }
  });





  //enable "confirm-changed-password" field if "change-password" input value length is greater than 7
  changePassword.addEventListener("input", function()
  {
    if (this.value.length > 7)
      confirmChangedPassword.disabled = false;
    else
      confirmChangedPassword.disabled = true;
  });





  //enable save button only when user's "current-password" has been input
  currentPassword.addEventListener("input", allow_save);
  currentPassword.addEventListener("focus", allow_save);
  
  function allow_save()
  {
    if (currentPassword.value)
      currentPassword.parentNode.style.background = 'rgb(0, 134, 184)';

    if (currentPassword.value.length > 7) 
      enable_save_button();
    else disable_save_button();
  }





  //save edited profile data
  saveButton.addEventListener("click", function(e)
  {
    e.preventDefault();

    let editFormData = new FormData(),
        profile_edited = false,
        dateTime = Utils.getDateTime();

    //if input value is not equal to current profile value, append input value to form data i.e. a change has been made and needs to be sent to the server
    for (let i = 0; i < allProfileInputs.length; i++)
      if (allProfileInputs[i].type != "password")
        if (allProfileInputs[i].name == "DOB" && allProfileInputs[i].value == sessionStorage.getItem("unformatted-DOB"))
          continue;
        else if (allProfileInputs[i].value.trim() != sessionStorage.getItem(allProfileInputs[i].name))
          editFormData.append(allProfileInputs[i].name, allProfileInputs[i].value);
    
    //if image is chosen/selected
    if (Q("#profile-edit-image-file").files[0])
      editFormData.append(Q("#profile-edit-image-file").name, Q("#profile-edit-image-file").files[0]);
    
    //if user wishes to change password, append password value to editFormData
    if (changePassword.value)
      if (confirmChangedPassword.value)
        editFormData.append(changePassword.name, changePassword.value),
        editFormData.append(confirmChangedPassword.name, confirmChangedPassword.value);
      else
      {
        disable_save_button();
        Utils.callInputFeedback(confirmChangedPassword.parentNode, "error", "⚠ Your new password has to be confirmed. Also ensure your new password length is not less than 8.", confirmChangedPassword.parentNode.parentNode);
        Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250);
        return;
      }
        
    //if form data has values, it implies profile has been edited (or input values have been changed) then flag profile edited true
    for (let prop of editFormData.values())
    {
      profile_edited = true;
      break;
    }
    
    //if profile is not edited (i.e. no input values changed), prevent further function execution (i.e. prevent from sending unchanged/same profile details to server)
    if (!profile_edited)
    {
      Utils.hideAllFeedbacks();
      disable_save_button();
      Utils.displayInputFeedback(saveButton.parentNode, "processing", `⚠ You did not make any edit or change to your profile. Click or tap "Dismiss" to dismiss edit mode.`, "44px");
      Utils.scrollPageTo(Q("body"), Q(".processing").offsetTop - 250);
      return;
    }

    editFormData.append("login-id", sessionStorage.getItem("email"));
    editFormData.append(currentPassword.name, currentPassword.value); //append current password to enable saving on server
    editFormData.append(this.name, "submit"); //append submit button to be used for processing FormData on server
    editFormData.append('date-time', dateTime);
    disable_save_button();
    saveButton.innerHTML = `${loader_sm.replace("type", l_arrow)} Saving...`;

    //Finally POST edited profile data. (POST() returns a Promise which has two callbacks passed to its "then()" method: resolve and reject)
    Utils.POST(editFormData, `./php/edit_profile_script.php?user=${USER}`).then
    (
      //POST request resolved
      function(responseText)
      {
        setTimeout(() =>
        {
          //responseText is a JSON stringified object of objects sent from server
          let responses = JSON.parse(responseText), response;

          for (let prop in responses)
          {
            response = JSON.parse(responses[prop])

            //show update feedbacks on error or on warning or on success
            if (Q(`.profile-detail-input-${prop}`))
              if (Q(`.profile-detail-input-${prop}`).parentNode.parentNode.querySelector(".feedback"))
                Utils.changeFeedbackType(Q(`.profile-detail-input-${prop}`).parentNode, response.type, response.message);
              else
                Utils.displayInputFeedback(Q(`.profile-detail-input-${prop}`).parentNode, response.type, response.message);

            if (/image\-name|image\-file/.test(prop))
              Utils.callInputFeedback(Q("#profile-edit-image-label"), response.type, response.message, Q("#profile-edit-image-label").parentNode);
          }

          //i.e. if there exists a successful POST datum and any unsuccessful POST datum, all edited profile data will not be saved to database, so throws warning
          if (Q(".success") && (Q(".error") || Q(".processing")))
            Utils.displayInputFeedback(saveButton.parentNode, "processing", `⚠ To update and save accepted profile edit inputs, attend to defaulty input values.`, "44px");

          //first scroll page to where an "error" feedback (red background) is found for user to behold and attend to else scrolls page to a "warning" feedback (orange background) else to a "success" feedback (blue background)
          if (Q(".error"))
            Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250); 
          else if (Q(".processing"))
            Utils.scrollPageTo(Q("body"), Q(".processing").offsetTop - 250);
          else if (Q(".success"))
          {
            Utils.updateClientSideProfile(responses);
            Utils.scrollPageTo(Q("body"), Q(".success").offsetTop - 250);
            changePassword.value = '';
            confirmChangedPassword.value = '';
            confirmChangedPassword.disabled = true;
          }
            
          saveButton.innerHTML = "✔ Save";
          currentPassword.value = "";
        }, 500);
      },
      //POST request rejected
      function(xhttp) 
      {
        Utils.hideAllFeedbacks();
        setTimeout(() =>
        {
          Utils.displayInputFeedback(saveButton.parentNode, "error", `Something went wrong while trying to update your profile. Kindly review your network settings and ensure you are connected to the internet.`, "60px");
          disable_save_button();
          disable_current_password();
          saveButton.innerHTML = "Save";
          Utils.scrollPageTo(Q("body"), Q(".error").offsetTop - 250);
        }, 500);
      }
    );
  });



  //did this in case of theme-color switch
  let inputsParentsInitialBgColor = allProfileInputs[1].parentNode.style.background;



  //deactivate profile edit mode
  function turnOnProfileEditMode()
  {
    for (let i = 0; i < allProfileInputs.length; i++)
    {
      if (allProfileValues[i])
      {
        allProfileValues[i].style.display = "none";

        if (allProfileInputs[i].name == "DOB")
          allProfileInputs[i].value = sessionStorage.getItem("unformatted-DOB") ? sessionStorage.getItem("unformatted-DOB") : '―';
        else
          allProfileInputs[i].value = sessionStorage.getItem(allProfileInputs[i].name);
      }
        
      allProfileInputs[i].style.display = "initial";
      allProfileInputs[i].parentNode.style.background = "#0086b8";

      if (allProfileInputs[i].type != "password")
        allProfileInputs[i].placeholder = `Edit your ${allProfileInputs[i].name}`;
    }

    Q("#profile-image").style.display = "none";
    Q("#profile-edit-image-label-container").style.display = "flex";
    Q(".change-password-wrapper").style.display = "flex";
    Q(".confirm-changed-password-wrapper").style.display = "flex";
    Q(".current-password-wrapper").style.display = "flex";
  }





  //activate profile edit mode
  function turnOffProfileEditMode()
  {
    let timeout = Q(".feedback") ? 375 : 0;

    setTimeout(() => 
    {
      for (let i = 0; i < allProfileInputs.length; i++)
      {
        allProfileInputs[i].style.display = "none";
        if (allProfileValues[i])
          allProfileValues[i].style.display = "initial";
        allProfileInputs[i].parentNode.style.background = inputsParentsInitialBgColor;
      }

      Q("#profile-edit-image-label-container").style.display = "none";
      Q(".current-password-wrapper").style.display = "none";
      Q(".change-password-wrapper").style.display = "none";
      Q(".confirm-changed-password-wrapper").style.display = "none";
      Q("#profile-image").style.display = "flex";
      Q(".profile-form").reset();
    }, timeout);

    disable_save_button();
    disable_current_password();
    Utils.hideAllFeedbacks();
  }




  
  function enable_current_password()
  { currentPassword.disabled = false; }

  function disable_current_password()
  { currentPassword.disabled = true; }

  function enable_save_button()
  { saveButton.disabled = false; }

  function disable_save_button()
  { saveButton.disabled = true; }
}