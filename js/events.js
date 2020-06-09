document.addEventListener("DOMContentLoaded", () => {
    const closeBtn = document.querySelectorAll(`.user__container .container__close`);

    for(let btn of closeBtn) {
        btn.addEventListener('click', (event) => {
            event.target.closest(`.user__container`).classList.toggle('user__container--show');
        });
    }
});