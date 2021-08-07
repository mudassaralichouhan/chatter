/*
 *
 */

// if session is exist on server then continue :)
( () => {
    chatterXHR( (data) => {
		if(data.status === false) {
			window.location.href = 'http://'+location.hostname+'/ui/login.html';
		}
    }, 'auth/session_exist.php', '', 'GET');
})();


// acknowledgement client to server
chatterXHR( (data) => {}, 'event/last-seen.php');
setInterval( () => {
	chatterXHR( (data) => {}, 'event/last-seen.php');
}, 60*1000);
