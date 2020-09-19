const loadEventListenners = () => {
    const togglePostCreatorBtn = document.querySelector(`.post__creator .btn__close`);
    const closeMenuFormBtns = document.querySelectorAll(`.user__bar .form__close`);
    const alertClose = document.querySelector(`.alert__close`);
    const formCloseBtn = document.querySelectorAll(`.form__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.input`);
    const menuBtns = document.querySelectorAll(`[class$="__button"]`);
    const passwordEyes = document.querySelectorAll(`.far[class*="fa-eye"]`);
    const createPostTime = document.querySelectorAll(`.post__creator time`)[0];
    const emailToUserBtns = document.querySelectorAll(`.btn__mail`);
    const recoveryBtn = document.querySelector(`.forgot`);
    const logoutBtn = document.querySelector(`.btn__logout`);
    const timeInterval = window.setInterval(() => createPostTime.innerText = new Date().toLocaleString(), 1000);

    // Add Event Listeners

    logoutBtn.addEventListener(`click`, () => logOut());

    inputs.forEach(input => {
        if(input.value) toggleFocusClass(input.closest(`.form__row`), true);
        input.addEventListener(`focus`, event => toggleFocusClass(event.target.closest(`.form__row`), true));
        input.addEventListener(`blur`, event => (!event.target.value) ? toggleFocusClass(event.target.closest(`.form__row`), false) : null);
    });

    passwordEyes.forEach(eye => {
        eye.addEventListener(`click`, (event) => {
            if(eye.classList.contains(`fa-eye`)) {
                eye.classList.remove(`fa-eye`);
                eye.classList.add(`fa-eye-slash`);
            } else {
                eye.classList.remove(`fa-eye-slash`);
                eye.classList.add(`fa-eye`);
            }

            event.preventDefault();
            let input = event.target.previousElementSibling;
            (input.type == `password`) ? input.type = `text` : input.type = `password`;
        });
    });

    menuBtns.forEach(btn => btn.addEventListener(`click`, () => {
        if(menuBtns[0] != btn && menuBtns[0].classList.contains(`--show`)) toggleShowClass(menuBtns[0], menuBtns[0].nextElementSibling);
        if(menuBtns[1] != btn && menuBtns[1].classList.contains(`--show`)) toggleShowClass(menuBtns[1], menuBtns[1].nextElementSibling);
        if(menuBtns[2] != btn && menuBtns[2].classList.contains(`--show`)) toggleShowClass(menuBtns[2], menuBtns[2].nextElementSibling);
        if(menuBtns[3] != btn && menuBtns[3].classList.contains(`--show`)) toggleShowClass(menuBtns[3], menuBtns[3].nextElementSibling);

        toggleShowClass(btn, btn.nextElementSibling);
    }));

    emailToUserBtns.forEach(btn => btn.addEventListener(`click`, () => {
        toggleShowClass(document.querySelector(`.form__mail`),  document.querySelector(`.mail__creator`));
        document.getElementById(`mail__recipient`).setAttribute(`value`, btn.getAttribute(`email`));
    }));

    hamburgerMenu.addEventListener(`click`, () => toggleShowClass(document.querySelector(`.hamburger`), document.querySelector(`.main__nav`)));

    togglePostCreatorBtn.addEventListener(`click`, () => toggleShowClass(togglePostCreatorBtn.closest(`.post__creator`)));

    alertClose.addEventListener(`click`, () => toggleShowClass(alertClose.closest(`.info__alert`)));

    formCloseBtn.forEach(btn => btn.addEventListener(`click`, () => toggleShowClass(btn.closest(`.form`), btn.closest(`.form`).previousElementSibling)));

    recoveryBtn.addEventListener(`click`, () => toggleShowClass(document.querySelector(`.form__recovery`)));

    toggleShowClass(menuBtns[3].parentElement);
};

document.addEventListener(`DOMContentLoaded`, loadEventListenners());