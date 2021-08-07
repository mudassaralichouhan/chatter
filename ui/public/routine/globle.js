/*
 * these functionalty generalize for whole site.
 */

const BASE_URL = 'http://'+location.hostname+'/ajax/';
localStorage.themeAttribute = 'theme';

function chatterXHR(calback, url, form = null, method = 'POST') {

    let xhr = new XMLHttpRequest();
    //xhr.open(method, BASE_URL+url, true);
    xhr.open(method, 'http://chatter.lovestoblog.com/ajax/'+url, true);
    xhr.onload = () => {
        if(xhr.readyState === XMLHttpRequest.DONE) {
            if(xhr.status === 200) {
                try {
                    calback( JSON.parse(xhr.response) );
                } catch (e) {
                    console.log('server response error');
                }
            }
        }
    };
    //xhr.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhr.send(form);
}

if( localStorage.themeMode === undefined ) {
    localStorage.themeMode = 'Dark';
}
document.querySelector('body').setAttribute(localStorage.themeAttribute, localStorage.themeMode);
