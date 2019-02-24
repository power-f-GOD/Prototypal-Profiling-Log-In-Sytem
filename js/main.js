
"use strict";





//selectors
var Q = document.querySelector.bind(document);
var QAll = document.querySelectorAll.bind(document);



//for loaders and loaders styling/output
const l_arrow = "loader-rotating-arrows",
      l_ovals = "loader-fading-ovals",
      l_balls = "loader-revolving-balls",
      l_connected = "loader-connected-arrows";



//loader sizes
let loader_sm = `<span class="loader-sm type">loader</span> `,
    loader_md = `<span><span class="loader-m type">loader</span><br />Loading...</span>`,
    loader_lg = `<span><span class="loader-l type">loader</span><br />Loading...</span>`,
    current_loader = "",

    //used to display an error message on page-load/request error
    request_status_info = `
      <div class="feedback-wrapper signup-status-feedback-wrapper box-shadow">
        <div class="feedback error">
          <h4>{status}</h4> 
          <span>{status_text}</span>
        </div>
      </div>`,

    //get url search parameters values
    urlParams = new URLSearchParams(window.location.search),
    USER = urlParams.get('user'),
    EMAIL = urlParams.get('email'),
    ID = urlParams.get('u_id'),
    HASH = urlParams.get('hash');





this.addEventListener("DOMContentLoaded", loadMainScript);

function loadMainScript()
{
  //fade-in page and navbar on window load
  setTimeout(() => Q(".navbar").classList.remove("hide"), 1500);
  setTimeout(() => Q("#custom-container").classList.remove("hide"), 500);



  
  //set previously chosen color theme by user
  if (localStorage.getItem('theme-color-href'))
    Q('.theme-color-changer').classList.remove('dark'),
    Q('#theme-color').href = localStorage.getItem('theme-color-href');





  //this code block is for a case where user opens multiple G-TECHLY tabs and eventually signs out in one of tabs and also to update changes to profiles if any made on tab switch
  window.addEventListener('visibilitychange', function()
  {
    if (document.visibilityState == 'visible')
    {
      //set previously chosen color theme by user on tab switch (if there be duplicates)
      if (localStorage.getItem('theme-color-href'))
        Q('.theme-color-changer').classList.remove('dark'),
        Q('#theme-color').href = localStorage.getItem('theme-color-href');
      else 
        Q('.theme-color-changer').classList.add('dark'),
        Q('#theme-color').href = '';  

      if (window.location.pathname.match(/home|profile/))
        Utils.GET(`./php/user_still_signed_in_check.php?user=${USER}&u_id=${ID}`).then
        (
          //GET resolved
          function(responseText)
          {
            let responses = JSON.parse(responseText),
                userStillSignedIn = Number(JSON.parse(responses['signed-in']).value);

            if (!userStillSignedIn)
            {
              setTimeout(() =>
              {
                Q('.user-not-signed-in-bg-overlay').classList.remove('hide'),
                Q('.user-not-signed-in-bg-overlay').classList.add('show');

                Q('body').onclick = function(e)
                {
                  if (e.target == this || e.target.classList.contains('user-not-signed-in-bg-overlay'))
                    window.location = 'index';//'http://localhost/g-techly/index';
                }
              }, 1000);
            }
            else
            {
              let id = JSON.parse(responses['id']).value;
              
              setTimeout(() =>
              {
                //if current tab URL u_id is != to response id, redirect to signin page
                if (ID != id)
                  window.location.assign(`http://localhost/g-techly/signin?user=0`);
                else
                {
                  Q('.user-not-signed-in-bg-overlay').classList.remove('show');
                  Q('.user-not-signed-in-bg-overlay').classList.add('hide');
                  Utils.updateClientSideProfile(responses);
                }
              }, 500);
            }
          },
          //GET rejected
          function (xhttp)
          {
            setTimeout(() => Q("#custom-container").innerHTML = request_status_info.replace("{status}", xhttp["status"]).replace("{status_text}", xhttp["status_text"]), 300);
          }
        );
    }
  });
    



  
  //replace history state on page load to avoid pushing duplicate contents to history.state object
  if (/\/index|\/g-techly\/$/.test(window.location.pathname))
    Utils.replaceStateOnPageLoad('Get Started - G-TECHLY', 'index', `index`);
  else if (window.location.pathname.match('/home'))
    Utils.replaceStateOnPageLoad('Home - G-TECHLY', 'home', USER ? `home?user=${USER}&u_id=${ID}` : 'home');
  else if (window.location.pathname.match('/signin'))
    Utils.replaceStateOnPageLoad('Sign In - G-TECHLY', 'signin', `signin`);
  else if (window.location.pathname.match('/signup'))
    Utils.replaceStateOnPageLoad('Sign Up - G-TECHLY', 'signup', `signup`);
  else if (window.location.pathname.match('/profile'))
    Utils.replaceStateOnPageLoad('Profile - G-TECHLY', 'profile', USER ? `profile?user=${USER}&u_id=${ID}` : 'profile');
  else if (window.location.pathname.match('/iforgot'))
    Utils.replaceStateOnPageLoad('iForgot Password - G-TECHLY', 'iforgot', `iforgot`);
  else if (window.location.pathname.match('/reset'))
    Utils.replaceStateOnPageLoad('Reset Password - G-TECHLY', 'reset', EMAIL ? `reset?email=${EMAIL}&hash=${HASH}` : 'reset');




  //add event listeners for getting AJAX requested pages to js--nav-links
  for (let i = 0; i < QAll(".js--nav-link").length; i++)
    QAll(".js--nav-link")[i].addEventListener("click", function()
    { Utils.get_page(this); });





  //window history state handler
  this.addEventListener("popstate", function(e)
  {
    if (e.state)
    {
      Q("#custom-container").className = "hide";
      setTimeout(() => 
      {
        Q("#custom-container").className = "show";
        Q("#custom-container").innerHTML = e.state.content;
        document.title = e.state.page_title;
        Utils.loadCurrentPageScript(e.state.url);
      }, 50);
    }
  });





  //shows/hides dark bg-overlay on click of navbar-toggler button
  Q(".navbar-toggler").addEventListener("click", function () 
  {
    if (/collapsed/.test(this.className))
      Q("#bg-overlay").className = "show";
    else 
      Q("#bg-overlay").className = "hide";
  });





  //if nav-links are not collapsed, collapses them on click of any point/position on page if not collapsed
  Q("body").addEventListener("click", () =>
  {
    if (!/collapsed/.test(Q(".navbar-toggler").className))
      Q(".navbar-toggler").click();
  });





  //goes to home page on click of G-TECHLY icon
  Q(".header-icon").addEventListener("click", () => Q(".home-link").click());





  //theme color changer: changes theme-color
  Q(".theme-color-changer").addEventListener("click", function() 
  {
    if (this.classList.contains('dark'))
    {
      this.classList.remove('dark');
      this.classList.add('light');
      Q("#theme-color").href = 'css/light_theme.css';
      localStorage.setItem('theme-color-href', 'css/light_theme.css');
    }
    else
    {
      this.classList.remove('light');
      this.classList.add('dark');
      Q("#theme-color").href = '';
      localStorage.setItem('theme-color-href', '');
    }
  });





  //change navbar toggler icon to user image if user is signed in
  if (window.location.pathname.match(/\/home|\/profile/))
  {
    let userImage = JSON.parse(localStorage.getItem(`u_${USER}`))['image-name'];
    Q('.navbar-toggler').innerHTML = '',
    Q('.navbar-toggler').style.backgroundImage = `url('${userImage}')`;
  }
    



  //load current page script on page load in case URL is changed and is not 'index'
  Utils.loadCurrentPageScript(window.location.pathname);
}
