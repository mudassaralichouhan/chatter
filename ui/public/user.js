/*
 *
 */

const searchBar = document.querySelector(".users .search input");
const searchBtn = document.querySelector(".users .search button");
const userList = document.querySelector(".users .user-list");
const logout = document.querySelector('.users header .logout');
const userImage = document.querySelector('.users .content img ');
const userName = document.querySelector('.users .details span a');

// logout user
logout.onclick = () => {
    let data = chatterXHR( (data) => {
        if(data.status) {
            location.href = 'login.html';
        }
    }, 'auth/logout.php');
};
function session_id() {
    return /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
}

// search users
searchBtn.onclick = () => {
    searchBar.classList.toggle('active');
    searchBtn.classList.toggle('active');
    searchBar.focus();
};
searchBar.onkeyup = () => { // search user
    let form = new FormData();
    console.log(searchBar.innerHTML);
    form.append('query', searchBar.value);
    let data = chatterXHR((data) => {
        if(data.status) {
            data = data['data'];
            userList.innerHTML = '';
            userListHTML(data);
        }
    }, 'search/search.php', form);
};

// sync user live or not\
function getUserList() {
    let data = chatterXHR( (data) => {
        if(data.status) {
            data = data['data'];
            userList.innerHTML = '';
            userListHTML(data);
        }
    }, 'user/users-list.php');
}
function userListHTML(userJson) {
    userJson.forEach( (value) => {

        let id = value['id'];
        let image = value['photo'];
        let userName = value['name'];
        let lastMsg = value['last_msg'];
        let status = value['status'].split(/[- :]/); // Split timestamp into [ Y, M, D, h, m, s ]
        const dt = new Date();
        let unreaded = value.unreaded;
        let unreadClass = '';
        if(unreaded > 0 ) {
            unreadClass = 'show'
        }

        if( status[0] == dt.getFullYear()&&status[1] == dt.getMonth()+1&&status[2] == dt.getDate() &&
            status[3] == dt.getHours()&&status[4] >= dt.getMinutes()-1) {
            status = '';
        } else {
            status = ' offline';
        }

        userList.innerHTML += '<a href="inbox.html?id='+id+'">' +
            '                        <div class="content">' +
            '                            <img src="'+image+'" alt="">' +
            '                            <span class="unread '+unreadClass+'">'+unreaded+'</span>' +
            '                            <div class="details">' +
            '                                <span>'+userName+'</span>' +
            '                                <p>'+lastMsg+'</p>' +
            '                            </div>' +
            '                        </div>' +
            '                        <div class="status-dot'+status+'"><i class="fas fa-circle"></i></div>' +
            '                    </a>';
    });
}
setInterval( () => {
    getUserList();
}, 10*1000);
getUserList();

// update user image
userImage.onclick = () => {
    let imageBrowser = document.createElement("INPUT");
    imageBrowser.setAttribute("type", "file");
    imageBrowser.setAttribute("name", "image");
    imageBrowser.click();

    const uploadFile = file => {
        let formData = new FormData();
        formData.append("image", file);

        chatterXHR( (data) => {
            window.location.reload();
        }, 'user/profile-image-update.php', formData);
    };

    imageBrowser.addEventListener("change", (event) => {
        const files = event.target.files;
        uploadFile(files[0]);
    });
};

// set user data to display
(() => {
    let data = chatterXHR( (data) => {
        if(data.status) {
            data = data['data'];
            userName.innerHTML = data.fname+' '+data.lname;
            userImage.src = data.photo;
        }
    }, 'user/user.php');
})();
