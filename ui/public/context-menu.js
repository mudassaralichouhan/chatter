/*
 *
 */

function contextMenu(){

    const body = document.querySelector('body');
    const chat = document.querySelectorAll(".chat-box .chat");
    const contextMenu = document.querySelector("#context-menu");
    const deleteItem = document.querySelector('#context-menu .delete');
    const copyItem = document.querySelector('#context-menu .copy');

    body.onmousemove = (e) => {
        x = e.clientX;
        y = e.clientY;
    };

    Array.from(chat).forEach( (element) => {
        element.addEventListener("contextmenu", () => {
            // event.preventDefault();

            const id = element.querySelector('p').getAttribute('id');

            contextMenu.style.top = `${y}px`;
            contextMenu.style.left = `${x}px`;
            contextMenu.classList.add("visible");

            //contextMenu.childNodes[1].setAttribute('id', element.getAttribute('id'));
            deleteItem.setAttribute('id', 'id-'+id);
            copyItem.setAttribute('id', 'copy-'+id);

        });
    });

    deleteItem.onclick = () => {
        let id = deleteItem.getAttribute('id').split('-')[1];
        deleteMessage(id);
        contextMenu.classList.remove("visible");
    };

    copyItem.onclick = () => {
        let id = deleteItem.getAttribute('id').split('-')[1];
        clipMessage(id);
        contextMenu.classList.remove("visible");
    };

    // remove context menu out-side single click
    body.addEventListener("click", (e) => {
        if (e.target.offsetParent != contextMenu) {
            contextMenu.classList.remove("visible");
        }
    });

}
