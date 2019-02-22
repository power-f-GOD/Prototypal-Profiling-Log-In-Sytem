'use strict';

function loadHomePageScript()
{
  let hour = new Date().getHours(),
      greeting = hour < 12 ? 'Good morning!' : (hour < 16 ? 'Good afternoon!' : 'Good evening!');

  Q(".greeting").innerHTML = greeting;
}