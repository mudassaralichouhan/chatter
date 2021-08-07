/*
 *
 */

const form = document.querySelector(".signup form");
const submit = document.querySelector(".signup form .button");
const errorText = document.querySelector(".signup .error-txt");



form.onsubmit = (e) => {
    e.preventDefault();
};
submit.onclick = () => {

    chatterXHR( (data) => {

        if(data.status) {
            errorText.style.display = 'none';
            location.href = 'login.html';
        } else {
            errorText.textContent = data.error_text;
            errorText.style.display = 'block';
        }

    }, 'auth/sign-up.php', (new FormData(form)) );
};
