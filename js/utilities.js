"use strict"





class Utils
{
  //shows feedback (errors, warnings or successes) on user form inputs and request statuses
  static showInputFeedback(caller, type, feedbackText, height, callerParent, feedbackTitle)
  {
    height = height ? height : "40px"; //set height for input feedback or request status feedback

    let showFeedbackTimeout = 100;

    callerParent = callerParent ? callerParent : caller.parentNode;
    feedbackTitle = !feedbackTitle ? (type == "success" ? "✔ Sign up successful!" : "⚠ Network Error!") : feedbackTitle;

    //display bigger feedback wrapper (if there be need) if not called/triggered by an input action
    let feedback = caller.type == "submit" ? `
      <div class="feedback-wrapper signup-status-feedback-wrapper">
        <div class="feedback ${type}">
          <h4 class="feedback-title">${feedbackTitle}</h4> 
          <span>${feedbackText}</span>
        </div>
      </div>` : `
      <div class="feedback-wrapper">
        <div class="feedback js--feedback ${type}"><div>${feedbackText}</div></div>
      </div>`;
    
    if (caller.type == "submit")
      Q("#sign-in-up-wrapper").insertAdjacentHTML('beforeend', feedback);
    else
      callerParent.insertAdjacentHTML('beforeend', feedback),
      setTimeout(() => callerParent.querySelector(".feedback-wrapper").style.height = height, showFeedbackTimeout);
  }
  




  //hides feeback
  static hideInputFeedback(caller, callerParent)
  {
    let timeout = "";
    
    callerParent = callerParent ? callerParent : caller.parentNode;
    
    if (caller.type == "submit")
      timeout = 0, callerParent = Q("#sign-in-up-wrapper");
    else timeout = 300;
    
    //checks if there be any feedback wrapper element to remove before performing remove function to avoid throwing any error
    if (callerParent.querySelector(".feedback-wrapper"))
      callerParent.querySelector(".feedback-wrapper").style.height = "0",
      setTimeout(() => 
      {
        if (callerParent.querySelector(".feedback-wrapper"))
          callerParent.removeChild(callerParent.querySelector(".feedback-wrapper"));
      }, timeout);
  }


  


  //changes feedback type (CSS style) e.g. from error to warning or to success and vice versa, if there exists a feedback element to avoid creating duplicate feedback elements
  static changeFeedbackType(caller, type, feedbackText, parent, feedbackTitle)
  {
    parent = parent ? parent : caller.parentNode;

    if (parent.querySelector(".feedback-wrapper"))
      parent.querySelector(".feedback").className = `feedback js--feedback ${type}`,
      parent.querySelector(".feedback").innerHTML = `<div>${feedbackText}</div>`;
  }





  //similar to showInputFeedback only that it checks if there be any feedback element and shows or changes accordingly to avoid adding (invisible) duplicate feedbacks to DOM and so scroll_page_to() function can function properly and as expected
  static callInputFeedback(caller, type, feedbackText, parent, feedbackTitle)
  {
    parent = parent ? parent : caller.parentNode;

    if (parent && parent.querySelector(".feedback"))
      this.changeFeedbackType(caller, type, feedbackText, feedbackTitle);
    else
      this.showInputFeedback(caller, type, feedbackText, "", parent, feedbackTitle);
  }





  //updates client side profile if any change has been made
  static updateClientSideProfile(responses)
  {
    for (let prop in responses)
    {
      let response = JSON.parse(responses[prop]);

      if (prop == 'image-name')
      {
        //storing image-name in local storage so that it can always be available and not only for a session
        localStorage.setItem(`u_${USER}`, `{"image-name": "${response.value}"}`);
        
        let userImage = JSON.parse(localStorage.getItem(`u_${USER}`))['image-name'];

        if (Q("#profile-image"))
          Q("#profile-image").style.backgroundImage = `url("${userImage}")`,
          Q("#profile-edit-image").style.backgroundImage = `url("${userImage}")`;

        Q(".navbar-toggler").style.backgroundImage = `url("${userImage}")`;
      }
      else
      {
        sessionStorage.setItem(prop, response.value);

        if (Q(`.profile-detail-value-${prop}`))
          Q(`.profile-detail-value-${prop}`).innerHTML = response.value;
        else if (Q(`.profile-${prop}`)) //"prop" expected to be DATE or DATETIME
          Q(`.profile-${prop}`).innerHTML = response.value;
      }
    }
  }




  //hides feedbacks [if there be any]
  static hideAllFeedbacks()
  {  
    if (Q('.feedback-wrapper'))
      for (let elem of QAll('.feedback-wrapper'))
        if (elem.parentNode.querySelector('.feedback-wrapper'))
          Utils.hideInputFeedback(elem);
        else if (elem.parentNode.parentNode.querySelector('.feedback-wrapper'))
          Utils.hideInputFeedback(elem.parentNode);
        else
          Utils.hideInputFeedback(elem);
  }





  //scrolls page to position
  static scroll_page_to(element, position)
  {
    let start = undefined;

    if (element.scrollTop > position)
      start = setInterval(() => 
      {
        element.scrollTop -= 20;

        if (element.scrollTop <= position || element.scrollTop == 0) 
          clearInterval(start);
      }, 0.25);
    else
      start = setInterval(() => 
      {
        element.scrollTop += 20;

        if (element.scrollTop >= position || element.scrollTop >= (element.scrollHeight - element.offsetHeight)) 
          clearInterval(start);
      }, 0.25);
  }





  //loads respective page scripts according to changed history state's URL
  static loadCurrentPageScript(url)
  {
    if (url.match('index'))
      loadIndexPageScript();
    else if (url.match('signup'))
      loadSignupPageScript();
    else if (url.match('signin'))
      loadSigninPageScript();
    else if (url.match('profile'))
      loadProfilePageScript();
    else if (url.match('iforgot'))
      loadiForgotPageScript();
    else if (url.match('reset'))
      loadResetPageScript();
    else if (url.match('home'))
      loadHomePageScript();
  }





  //replaces history state accordingly to avoid 'history.state' from being equal to 'null'
  static replaceStateOnPageLoad(page_title, url, stateURL)
  {
    document.title = page_title;
    history.replaceState(
    {
      "content": Q("#custom-container").innerHTML,
      "page_title": page_title,
      "url": url
    }, page_title, `/g-techly/${stateURL}`);
  }





  //changes history state onclick of browser refresh, back or forward buttons
  static changeHistoryState(responseText, title, url)
  {
    let main_url = `${url.slice(0, /\?/.test(url) ? url.indexOf('?') : url.length)}`,
        test_url = new RegExp(main_url);
        
    Q("#custom-container").innerHTML = responseText;
    document.title = title;
    
    if (test_url.test(window.location.pathname))
      history.replaceState(
      {
        "content": responseText, 
        "page_title": title,
        "url": url
      }, title, `/g-techly/${url}`);
    else
      history.pushState(
      {
        "content": responseText,
        "page_title": title,
        "url": url
      }, title, `/g-techly/${url}`);
    
    this.loadCurrentPageScript(main_url);
  }





  //gets AJAX requested pages
  static get_page(anchor)
  {
    let url = anchor.getAttribute("data-href"),
        Utils = this;
      
    Q("#custom-container").innerHTML = loader_md.replace("type", "loader-animated-loading");

    //GET() returns a Promise which takes two callbacks passed to its "then()" method: resolve and reject
    Utils.GET(USER && ID ? `${url}?user=${USER}&u_id=${ID}` : url).then
    (
      //GET request resolved
      function(responseText)
      {
        //animate fade out and fade in effect on load of AJAX requested page
        setTimeout(() => 
        {
          Q("#custom-container").className = "hide";
          setTimeout(() => 
          {
            if (url.match("/signup"))
              Utils.changeHistoryState(responseText, "Sign Up - G-TEC", "signup");
            else if (url.match("/index"))
              Utils.changeHistoryState(responseText, "Get Started - G-TEC", "index");
            else if (url.match("/profile"))
              Utils.changeHistoryState(responseText, "Profile - G-TEC", `profile?user=${USER}&u_id=${ID}`);
            else if (url.match("/signin"))
              Utils.changeHistoryState(responseText, "Sign In - G-TEC", `signin`);
            else if (url.match('/home'))
              Utils.changeHistoryState(responseText, "Home - G-TEC", `home?user=${USER}&u_id=${ID}`);
            else if (url.match('/iforgot'))
              Utils.changeHistoryState(responseText, "iForgot Password - G-TEC", `iforgot`);
            else if (url.match('/reset'))
              Utils.changeHistoryState(responseText, "Reset Password - G-TEC", `reset`);

            Q("#custom-container").className = "show";
          }, 200);
        }, 400);
      },
      //GET request rejected
      function(xhttp)
      {
        setTimeout(() => Q("#custom-container").innerHTML = request_status_info.replace("{status}", xhttp["status"]).replace("{status_text}", xhttp["status_text"]), 300);
      }
    );
  };





  //POSTs user data to server
  static POST(data, url)
  {
    //POST returns a Promise object
    return new Promise(function(resolve, reject) 
    {
      let xhttp = new XMLHttpRequest();

      xhttp.onreadystatechange = function()
      {
        if (xhttp.readyState == 4 && xhttp.status == 200)
          resolve(this.responseText);
        else if (this.status == 4)
          reject("⚠ Error!");
      };
      xhttp.onerror = () => reject("⚠ Network Error!");

      xhttp.open("POST", url, true);
      // xhttp.setRequestHeader("Content-type", "multipart/form-data");
      xhttp.send(data);
    });
  }



  

  //GETs data from server
  static GET(url)
  {
    //GET returns a Promise object
    return new Promise(function (resolve, reject)
    {
      let xhttp = new XMLHttpRequest();

      xhttp.onreadystatechange = function()
      {
        if (this.readyState == 4 && this.status == 200)
          resolve(this.responseText);
        else if (this.readyState == 4)
        {
          let status = this.statusText ? `⚠ ${this.status} ${this.statusText}!` : "⚠ Network Error!",
              status_text = this.status == 404 ? "Sorry, the requested page was not found." : "Could not process request. <br />Please, check that you are connected to the internet then try again.";
          reject({"status": status, "status_text": status_text});
        }
      };

      xhttp.open("GET", url, true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send();
    });
  }
}




























