'use strict';





function loadHomePageScript()
{
  if (!Q(".greeting"))
    return;

  let hour = new Date().getHours(),
      greeting = hour < 12 ? 'Good morning!' : (hour < 16 ? 'Good afternoon!' : 'Good evening!');

  Q(".greeting").innerHTML = greeting;
}