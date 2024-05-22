// Description: This file is used to check if the user is logged in or not.

window.addEventListener("load", function() {
// This function is called when the page is loaded.
// It sends a request to the server to check if the user is logged in or not.
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
    // This function is called when the server responds to the request.
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText.startsWith("redirect:")) {
                // If the server responds with a redirect message, the user is not logged in.
                // The user is redirected to the index page.
                window.location.href = this.responseText.split(':')[1];
            } else {
                console.log("User is logged in");
            }
        }
    };
    // Send a request to the check.php script to check if the user is logged in.
    xhr.open("GET", "check.php", true);
    xhr.send();
  });