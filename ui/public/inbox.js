/*
 *
 */

const chatterId = window.location.href.split('?')[1].split('=')[1];

const form = document.querySelector('.typing-area');
const typedText = document.querySelector('.typing-area .typed-text');
const uploadMsgBtn = document.querySelector('.typing-area button');
const sendMsgBtn = document.querySelector('.typing-area button:last-child');
const inbox = document.querySelector('.chat-box');
const userImage = document.querySelector('.chat-area img');
const userName = document.querySelector('.chat-area .details span');
const userStatus = document.querySelector('.chat-area .details p');

var userPhotoSrc = '';

let msg_id = 0; // user in get-chat function

// prevent form submition
form.onsubmit = (e) => {
    e.preventDefault();
};

// send message to another user
sendMsgBtn.onclick = () => {
    let formdata = new FormData(form);
    formdata.append('chat-id', chatterId);
    chatterXHR( (data) => {
        if(data.status) {
            typedText.value = '';
            scrollToButtom();
        }
    }, 'messages/inbox.php', formdata);
};
uploadMsgBtn.onclick = () => {
    let uploadMsgBtn = document.createElement("INPUT");
    uploadMsgBtn.setAttribute("type", "file");
    uploadMsgBtn.click();

    const uploadFile = file => {
        let formData = new FormData(form);
        formData.append("media", file);
        formData.append("id", chatterId);
        chatterXHR( (data) => {
            if(data.status) {
                typedText.value = '';
                scrollToButtom();
            } else {
				alert(data.error_text);
			}
        }, 'messages/send-message-media.php', formData);
    };

    uploadMsgBtn.addEventListener("change", (event) => {
        const files = event.target.files;
		if( files[0].size < 10*1024*1024 ) {
			uploadFile(files[0]);
		} else {
			alert('file size exceeded');
		}
			
    });
};

// scroll chat box to end bottom
function scrollToButtom() {
    inbox.scrollTop = inbox.scrollHeight;
}

// live chatter on typing
function is_typing() {
    let param = new FormData();
    param.append('id',chatterId);

    if(chatterXHR( (data) => {
        if(data.status) {
            userStatus.innerHTML = 'Typing.';
            setTimeout( () => {userStatus.innerHTML = 'Typing..'} , 1000);
            setTimeout( () => {userStatus.innerHTML = 'Typing....'} , 2000);
        } else {
            userStatus.innerHTML = 'online';
        }
    }, 'event/is-typed.php', param));

}
setInterval( () => {is_typing()}, 10000);
is_typing();

// when current user is typed message
typedText.onclick = () => {
    chatterXHR( () => {}, 'event/i-am-typing.php');
};

// get chatter last seen
function getLastSeen() {
    let param = new FormData();
    param.append('id',chatterId);
    chatterXHR( (data) => {
        if(data.status) {
            data = data['data'];
            userStatus.innerHTML = data.last_seen;
        }
    }, 'event/seen.php', param);
}
setInterval( () => {getLastSeen()}, 5*1000);
getLastSeen();

// set user data to display
( () => {
    let form = new FormData();
    form.append('id', chatterId);

    chatterXHR( (data) => {
        if(data.status) {
            data = data['data'];
            userName.innerHTML = data.fname+' '+data.lname;
            userImage.src = data.photo;
            userPhotoSrc = data.photo;
        }
    }, 'user/user.php', form);

})();

// get user chat form sever
let syncChat = false;
function getChat() {
    let form = new FormData();
    form.append('id', chatterId);
    form.append('msg_id', msg_id);

    let data = chatterXHR( (data) => {
        if(data.status) {
            let chat = data['data'];

            msg_id = chat[chat.length-1].id;
			syncChat = true;
            inbox.insertAdjacentHTML('beforeend',chatHTML(chat));
            // scroll to buttom because message coming
            if(inbox.scrollTop > (inbox.scrollHeight - inbox.clientHeight)-300) {
                scrollToButtom();
                let form = new FormData();
                form.append('id', chatterId);
                chatterXHR( (data) => {}, 'messages/read_message.php', form);
            }
            setTimeout( ()=>{
                contextMenu();
                messageDetail();
            }, 500);
        }
    }, 'messages/get.php', form);
}
function chatHTML(jsonChat){
    let html = '';
    jsonChat.forEach( (chat) => {
        let msg = chat.message;
        let time = chat.date_time;

        if(chat.type === 'text') {

            if(chat.sender === chatterId) { // simple text message portion
                html += '<div class="chat incoming">' +
                '                        <img src="'+userPhotoSrc+'" alt="">' +
                '                        <div class="details">' +
                '                            <p id="'+chat.id+'">'+msg+'</p>' +
                '                            <span>'+time+'</span>' +
                '                        </div>' +
                '                    </div>';

            } else {
                html += '<div class="chat outgoing">' +
                    '                        <div class="details">' +
                    '                            <p id="'+chat.id+'">'+msg+'</p>' +
                    '                            <span>'+time+'</span>' +
                    '                        </div>' +
                    '                    </div>';
            }

        } else if(chat.type === 'image') { // image portion

            if(chat.sender === chatterId) {
                html += '<div class="chat incoming">\n' +
                    '                        <img src="'+userPhotoSrc+'" alt="">\n' +
                    '                        <div class="media details">\n' +
                    '                            <img src="'+chat.content+'" alt=""/>\n' +
                    '                            <p id="'+chat.id+'">'+msg+'</p>\n' +
                    '                            <span class="">'+time+'</span>\n' +
                    '                        </div>\n' +
                    '                    </div>';
            } else {
                html += '<div class="chat outgoing">\n' +
                    '                        <div class="media details">\n' +
                    '                            <img src="'+chat.content+'" alt=""/>\n' +
                    '                            <p id="'+chat.id+'">'+msg+'</p>\n' +
                    '                            <span>'+time+'</span>\n' +
                    '                        </div>\n' +
                    '                    </div>';
            }

        } else { // video portion

            if(chat.sender === chatterId) {
                html += '<div class="chat incoming">\n' +
                    '                        <img src="'+userPhotoSrc+'" alt="">\n' +
                    '                        <div class="media details">\n' +
                    '                            <video width="300" controls>\n' +
                    '                                <source src="'+chat.content+'" type="video/mp4">\n' +
                    '                                Your browser does not support HTML video.\n' +
                    '                            </video>\n' +
                    '                            <p id="'+chat.id+'">'+msg+'</p>\n' +
                    '                            <span>'+time+'</span>\n' +
                    '                        </div>\n' +
                    '                    </div>';
            } else {
                html += '<div class="chat outgoing">\n' +
                    '                        <div class="media details">\n' +
                    '                            <video controls>\n' +
                    '                                <source src="'+chat.content+'" type="video/mp4">\n' +
                    '                                Your browser does not support HTML video.\n' +
                    '                            </video>\n' +
                    '                            <p id="'+chat.id+'">'+msg+'</p>\n' +
                    '                            <span>'+time+'</span>\n' +
                    '                        </div>\n' +
                    '                    </div>';
            }
        }
    });
    return html;
}
getChat();
setInterval( () => {
	if(syncChat) {
		getChat();		
	}
}, 5000);

// message managing
function deleteMessage(id) {
    let param = new FormData();
    param.append('id', id);
    document.getElementById(id).parentElement.parentElement.remove();
    chatterXHR( (data) => {}, 'messages/delete-message.php', param);
    return true
}
function clipMessage(id) {
    let copytext = document.getElementById(id).textContent;
    navigator.clipboard.writeText(copytext);
    return true
}
function messageDetail() {
    let chat = document.querySelectorAll('.chat-box .chat');
    Array.from(chat).forEach( (element) => {
        element.onclick = () => {
            try {
                document.querySelector('.show').classList.toggle('show');
            } catch (e) {
            }
            element.querySelector('span').classList.toggle('show');
        }
    });
}

// persistence fetch messages
let offset = 10;
inbox.onscroll = () => {
    if(inbox.scrollTop === 0) {
        // fetch more chat on scroll-end

        let form = new FormData();
        form.append('id', chatterId);
        form.append('offset', offset);

        chatterXHR( (data) => {
            if(data.status) {
                let chat = data['data'];
                inbox.insertAdjacentHTML('afterbegin',chatHTML(chat));
                offset += 10;
                setTimeout( ()=>{
                    contextMenu();
                    messageDetail();
                }, 500);
            }
        }, 'messages/get.php', form);

    }
};
