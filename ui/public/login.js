/*
 *
 */

const form = document.querySelector(".login form");
const submit = document.querySelector(".login form .button");
const errorText = document.querySelector(".login .error-txt");

form.onsubmit = (e) => {
    e.preventDefault();
};
submit.onclick = () => {
    chatterXHR( (data) => {
        if(data.status) {
            location.href = 'user.html';
        } else {
            errorText.textContent = data.error_text;
            errorText.style.display = 'block';
        }
    },'auth/login.php', (new FormData(form)) );
};