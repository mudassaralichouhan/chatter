/*
 *
 */

const themeToggleBtn = document.querySelector('body button');
const themeToggleBtnText = document.querySelector('body button span');
const assign = document.querySelector('body');

themeToggleBtnText.innerHTML = assign.getAttribute( localStorage.themeAttribute );

themeToggleBtn.onclick =  () => {
    const themeMode = assign.getAttribute( localStorage.themeAttribute );
    themeToggleBtnText.innerHTML = themeMode;

    if(localStorage.themeMode === 'Light') {
        assign.setAttribute(localStorage.themeAttribute, 'Dark');
        themeToggleBtnText.innerHTML = localStorage.themeMode;
        localStorage.themeMode = 'Dark';
    } else {
        assign.setAttribute(localStorage.themeAttribute, 'Light');
        localStorage.themeMode = themeMode;
        themeToggleBtnText.innerHTML = localStorage.themeMode;
        localStorage.themeMode = 'Light';
    }
};
