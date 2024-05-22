document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(function() {
        const alertMessage = getCookie("username_taken");
        if (alertMessage !== "") {
            alert(alertMessage);
        //Sletter cookie efter den er blevet vist
        document.cookie = "alert=; Max-Age=0; path=/;";
        }
    }, 500);
});
  
function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(nameEQ) == 0) {
          return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
    }
    return "";
}