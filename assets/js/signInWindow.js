let signInWindow = document.getElementById('sign-in-window');

function showPopUp()
{
	signInWindow.removeAttribute("hidden");
}

function hidePopUp()
{
	signInWindow.setAttribute("hidden","");
}