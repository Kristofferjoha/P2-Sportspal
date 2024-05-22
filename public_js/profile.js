document.addEventListener("DOMContentLoaded", function() {
    fetch('profile.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.error) {
            console.error('Error fetching data:', data.error);
            return;
        }

        document.querySelector('input[name="name"]').value = data.name;
        document.querySelector('input[name="ageInput"]').value = data.age;
        document.querySelector('select[name="genderInput"]').value = data.gender;
        document.querySelector('input[name="phoneInput"]').value = data.phone;
        document.querySelector('select[name="experienceInput"]').value = data.niveau;

        // Check the goals checkboxes
        if (data.goals && Array.isArray(data.goals)) {
            data.goals.forEach(goal => {
                let checkbox = document.querySelector(`input[name="goals[]"][value="${goal}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        if (data.interests && Array.isArray(data.interests)) {
            data.interests.forEach(interest => {
                let checkbox = document.querySelector(`input[name="interests[]"][value="${interest}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
    });

    document.getElementById('settings-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('profile.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log('Success:', data);
          setCookie("Profile", "Successful submit", 86400); // Set the cookie for 1 day
          checkCookie();
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      });

      function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 86400);
        document.cookie = `${name}=${value}; expires=${expires.toUTCString()}; path=/;`;
      }
    
      function checkCookie() {
        const cookie = getCookie("Profile");
        if (cookie === "Successful submit") {
          alert("Profile succesfully updated!");
          // Clear the cookie after it has been displayed
          document.cookie = "Profile=; Max-Age=0; path=/;";
        }
      }
    
      function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') c = c.substring(1);
          if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length, c.length);
          }
        }
        return "";
      }
    });