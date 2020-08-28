const loadEventListenners = () => {
    const togglePostCreatorBtn = document.querySelector(`.post__creator .btn__close`);
    const closeMenuFormBtns = document.querySelectorAll(`.user__bar .form__close`);
    const alertClose = document.querySelector(`.alert__close`);
    const windowFormClose = document.querySelectorAll(`.window__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.input`);
    const menuBtns = document.querySelectorAll(`[class$="__button"]`);
    const eyes = document.querySelectorAll(`.far[class*="fa-eye"]`);
    const time = document.querySelectorAll(`.post__creator time`)[0];
    const emailButtons = document.querySelectorAll(`.btn__mail`);
    const recoveryBtn = document.querySelector(`.forgot`);
    const logoutBtn = document.querySelector(`.btn__logout`);
    const timeInterval = window.setInterval(() => time.innerText = new Date().toLocaleString(), 1000);

    // Add Event Listeners

    logoutBtn.addEventListener(`click`, () => logOut());

    inputs.forEach(input => {
        if(input.value) toggleFocusClass(input.closest(`.form__row`), true);
        input.addEventListener(`focus`, event => toggleFocusClass(event.target.closest(`.form__row`), true));
        input.addEventListener(`blur`, event => (!event.target.value) ? toggleFocusClass(event.target.closest(`.form__row`), false) : null);
    });

    eyes.forEach(eye => {
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

        toggleShowClass(btn, btn.nextElementSibling);
    }));

    emailButtons.forEach(btn => btn.addEventListener(`click`, () => {
        toggleShowClass(document.querySelector(`.form__mail`),  document.querySelector(`.mail__creator`));
        document.getElementById(`mail__recipient`).setAttribute(`value`, btn.getAttribute(`mail`));
    }));

    hamburgerMenu.addEventListener(`click`, () => toggleShowClass(document.querySelector(`.hamburger`), document.querySelector(`.main__nav`)));

    togglePostCreatorBtn.addEventListener(`click`, () => toggleShowClass(togglePostCreatorBtn.closest(`.post__creator`)));

    // for(let btn of closeMenuFormBtns) btn.addEventListener(`click`, () => toggleShowClass(btn.closest(`.form`), btn.closest(`.form`).previousElementSibling));

    alertClose.addEventListener(`click`, () => toggleShowClass(alertClose.closest(`.info__alert`)));

    windowFormClose.forEach(btn => btn.addEventListener(`click`, () => toggleShowClass(btn.closest(`.form`))));

    recoveryBtn.addEventListener(`click`, () => toggleShowClass(document.querySelector(`.form__recovery`)));

    toggleShowClass(menuBtns[2].parentElement);
};

document.addEventListener(`DOMContentLoaded`, loadEventListenners());