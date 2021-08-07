/*
 *
 */

const form = document.querySelector(".update form");
const submit = document.querySelector(".update form .button input");
const errorText = document.querySelector(".update .error-txt");

form.onsubmit = (e) => {
    e.preventDefault();
};

submit.onclick = () => {
    let data = chatterXHR((data) => {
        if (data.status) {
            errorText.style.display = 'none';
            submit.disabled = true;
        } else {
            errorText.textContent = data.error_text;
            errorText.style.display = 'block';
        }
    }, 'user/update.php', new FormData(form));
};

// set user data to display
(() => {
    chatterXHR( (data) => {
        if(data.status) {
            data = data['data'];
            form['fname'].value = data.fname;
            form['lname'].value = data.lname;
            form['email'].value = data.email;
        }
    }, 'user/user.php');
})();
