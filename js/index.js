
function loadIndexPageScript()
{
  //Goto sign-up page onclick of "GET STARTED" button
  Q("#get-started-button").addEventListener("click", () => Utils.get_page(Q(".signup-link")));
}