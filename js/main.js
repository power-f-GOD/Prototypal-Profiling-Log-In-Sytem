
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





this.addEventListener("DOMContentLoaded", function()
{
  //fade-in page and navbar on window load
  setTimeout(() => Q(".navbar").classList.remove("hide"), 1500);
  setTimeout(() => Q("#custom-container").classList.remove("hide"), 500);





  //for fullscreen toggling
  let doc = window.document,
  docEl = doc.documentElement,
  requestFullscreen = docEl.requestFullscreen || docEl.webkitRequestFullScreen || docEl.mozRequestFullScreen || docEl.msRequestFullscreen,
  exitFullscreen = doc.exitFullscreen || doc.webkitExitFullScreen || doc.mozCancelFullScreen || doc.msExitFullscreen;
  
  if (requestFullscreen)
    Q(".theme-color-changer").addEventListener('dblclick', function() 
    {
      if (window.innerHeight == screen.height || this.classList.contains('fullscreen-enabled'))
        exitFullscreen.call(doc),
        this.classList.remove('fullscreen-enabled');
      else
        requestFullscreen.call(docEl),
        this.classList.add('fullscreen-enabled');
    });
  
  



  if (location.pathname.match(/\/index|\/$|\/home/))
    Q('.header-icon').style.opacity = '0.15';
  else
    Q('.header-icon').style.opacity = '1';





  if (!sessionStorage.getItem('userHasVisited') && window.location.pathname.match(/\/index|\/$/))
  {
    if (requestFullscreen)
      alert('You can toggle full screen by double tapping/clicking the theme-color toggler button at the bottom-right of your screen.');
    
    setTimeout(() => 
    {
      Utils.displayModal(
      {
        title: 'Welcome! ✌',
        message: `Hi, there! Welcome to G-TECHLY. 😊 <br /><br />This is my very first Full-stack->Server-side Web Development project which is actually a sample or prototypal profiling system (AJAX powered) in which one (a user) can register/sign up, sign in, edit their profile and then sign out; inspired by SDC-UNN's (Software Developer's Club, University of Nigeria, Nsukka) Holiday Challenge/Assignment/Project.😊<br /><br />So, sign up, sign in, edit your profile, sign out, test and just surf it then rate it by sending a d.m. ...😊 Also, if you notice any bugs (errors), don't hesitate to send a d.m. as well. 😉✌.<br /><br /><i>You can send a direct instant WhatsApp message to me by clicking/tapping on my name below the sign up form. Thanks.</i>✌<br /><br />And I want to use this medium to give thanks to Brother Joshua (Senior Software Dev.) of Christ Embassy, AIT (CEAIT) who persuaded me to [freely] host G-TECHLY [on the internet] and who has also been a source of motivation. I actually never wanted to host G-TECHLY neither did I ever think of hosting it, but I now have [thanks to him]😄 and I've indeed learnt some stuff doing so.😊<br /><br />Also, to my brother, Bassey, whose PC for most of the project I used, and indeed, to my able <i>'adopted'</i> father, Mr. Lawal Moshood😊, who, of a truth, has been a blessing and who also helped in acquiring the PM (Personal Machine😉) I used in completing and hosting G-TECHLY. <br /><br />There are indeed more people to thank but I'll limit my thanksgiving to this project alone.😉<br /><br />Lastly, my biggest thanks goes to my Father who art in heaven (God Almighty, the Father of glory), for everything and everything.😊<br /><br />So, finally, this 'little' work of mine means much to me...😌 Hence, we' moving onward to greater things...<br /><br />Thanks for stopping by.😊✌<br /><br /><br />- Power'f GOD⚡⚡<br /><br /><br /><br /><a href'javascript:void(0)' class='default-action-handler' style='cursor: pointer; color: #02bffd;'>Click to Continue...</a>`,
        defaultAction: true
      });
      sessionStorage.setItem('userHasVisited', 1);
    }, 2200);
  }




  
  //set previously chosen color theme by user
  if (localStorage.getItem('theme-color-href'))
  {
    Q('.theme-color-changer').classList.remove('dark'),
    Q('#theme-color').href = localStorage.getItem('theme-color-href');
    Q('#meta-theme-color').content = localStorage.getItem('meta-theme-color');
  }





  //this code block is for a case where user opens multiple G-TECHLY tabs and eventually signs out in one of tabs and also to update changes to profiles if any made on tab switch
  window.addEventListener('visibilitychange', function()
  {
    if (document.visibilityState == 'visible')
    {
      //set previously chosen color theme by user on tab switch (if there be duplicates)
      if (localStorage.getItem('theme-color-href'))
      {
        Q('.theme-color-changer').classList.remove('dark');
        Q('#theme-color').href = localStorage.getItem('theme-color-href');
        Q('#meta-theme-color').content = localStorage.getItem('meta-theme-color');

      }
      else
      {
        Q('.theme-color-changer').classList.add('dark');
        Q('#theme-color').href = '';
        Q('#meta-theme-color').content = localStorage.getItem('meta-theme-color');
      }
        

      if (window.location.pathname.match(/home|profile|users/))
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
                Utils.displayModal(
                {
                  title: '⚠ Not Signed In',
                  message: `Sorry, you are no longer signed in. Please, <a href='signin?user=0'>sign in</a> to continue.`,
                });
              }, 1000);
            }
            else
            {
              let id = JSON.parse(responses['id']).value,
                  user = JSON.parse(responses['user']).value;
             
              setTimeout(() =>
              {
                //if current tab URL u_id is != to response id (i.e. signed in user is not equal to current tab user), then redirect to signin page
                if (ID != id)
                  window.location.assign(`signin?user=0`);
                else
                {
                  let path = window.location.pathname,
                      url = path.slice(path.lastIndexOf('/') + 1);

                  //modify browser url user-index parameter accordinlgy to signed in user-index
                  history.replaceState(
                  {
                    "url": url
                  }, document.title, `/g-techly/${url}?user=${user}&u_id=${id}`);
                  
                  //i.e. if diplayed modal is not a confirm box, hide modal accordingly
                  if (Q('.action-buttons-wrapper').style.display == 'none')
                    Q('.action-bg-overlay').classList.add('hide');

                  Utils.updateClientSideProfile(responses);
                }
              }, 500);
            }
          },
          //GET rejected
          function (xhttp)
          {
            setTimeout(() => 
            {
              Utils.displayModal(
              {
                title: `${xhttp['status']}`,
                message: `Something went wrong. Please, review your network settings and check that you are connected to the internet.<br /><br /><a href'javascript:void(0)' class='default-action-handler' style='cursor: pointer; color: #02bffd;'>Okay</a>`,
                defaultAction: true
              });
            }, 300);
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
  else if (window.location.pathname.match('/users'))
    Utils.replaceStateOnPageLoad('Users - G-TECHLY', 'users', USER ? `users?user=${USER}&u_id=${ID}` : 'users');
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

      if (location.pathname.match(/\/index|\/$|\/home/))
        Q('.header-icon').style.opacity = '0.15';
      else
        Q('.header-icon').style.opacity = '1';
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
      Q("#theme-color").href = 'css/light_theme.css';
      Q('#meta-theme-color').content = '#009ed3';
      localStorage.setItem('theme-color-href', 'css/light_theme.css');
      localStorage.setItem('meta-theme-color', '#009ed3');
    }
    else
    {
      this.classList.add('dark');
      Q("#theme-color").href = '';
      Q('#meta-theme-color').content = '#002b50';
      localStorage.setItem('theme-color-href', '');
      localStorage.setItem('meta-theme-color', '#002b50');
    }
  });





  //change navbar toggler icon to user image if user is signed in
  if (window.location.pathname.match(/\/home|\/profile|\/users/))
  {
    let userImage = JSON.parse(localStorage.getItem(`u_${USER}`))['image-name'];
    Q('.navbar-toggler').innerHTML = '',
    Q('.navbar-toggler').style.backgroundImage = `url('${userImage}')`;
  }
    




  //load current page script on page load in case URL is changed and is not 'index'
  Utils.loadCurrentPageScript(window.location.pathname);
});


