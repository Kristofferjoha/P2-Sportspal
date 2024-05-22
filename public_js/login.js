const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

registerLink.onclick = () => {
    wrapper.classList.add('active');
};

loginLink.onclick = () => {
    wrapper.classList.remove('active');
};

btnPopup.onclick = () => {
    wrapper.classList.add('active-popup');
};

iconClose.onclick = () => {
    wrapper.classList.remove('active-popup');
    wrapper.classList.remove('active');
};

document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(function() {
        const alertMessage = getCookie("invalid_password");
        if (alertMessage !== "") {
            alert(alertMessage);
        //Sletter cookie efter den er blevet vist
        document.cookie = "invalid_password=; Max-Age=0; path=/;";
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

document.addEventListener('DOMContentLoaded', (event) => {
    setTimeout(function() {
        const alertMessage = getCookie("invalid_username");
        if (alertMessage !== "") {
            alert(alertMessage);
        //Sletter cookie efter den er blevet vist
        document.cookie = "invalid_username=; Max-Age=0; path=/;";
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

  