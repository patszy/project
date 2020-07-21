const loadEventListenners = () => {
    const togglePostCreatorBtn = document.querySelector(`.post__creator .btn__close`);
    const closeFormBtns = document.querySelectorAll(`.user__bar .form__close`);
    const alertClose = document.querySelector(`.alert__close`);
    const mailClose = document.querySelector(`.mail__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.input`);
    const menuBtns = document.querySelectorAll(`[class$="__button"]`);
    const eyes = document.querySelectorAll(`.far[class*="fa-eye"]`);
    const time = document.querySelectorAll(`.post__creator time`)[0];
    const emailButtons = document.querySelectorAll(`.btn__mail`);

    const toggleShowClass = (element, parent = null) => {
        if(element) element.classList.toggle(`--show`);
        if(parent) parent.classList.toggle(`--show`);
    }

    const toggleFocusClass = (element, show) => {
        show ? element.classList.add(`--focus`) : element.classList.remove(`--focus`);
    };

    const timeInterval = window.setInterval(() => time.innerText = new Date().toLocaleString(), 1000);

    // Add Event Listeners

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
        menuBtns.forEach(btn => {if(btn.classList.contains(`--show`)) toggleShowClass(btn, btn.nextElementSibling)});
        toggleShowClass(btn, btn.nextElementSibling);
    }));

    emailButtons.forEach(btn => btn.addEventListener(`click`, () => {
        toggleShowClass(document.querySelector(`.form__mail`),  document.querySelector(`.mail__creator`));
        document.getElementById(`mail__recipient`).setAttribute(`value`, btn.getAttribute(`mail`));
    }));

    hamburgerMenu.addEventListener(`click`, event => toggleShowClass(event.target, document.querySelector(`.main__nav`)));

    togglePostCreatorBtn.addEventListener(`click`, () => toggleShowClass(togglePostCreatorBtn.closest(`.post__creator`)));

    for(let btn of closeFormBtns) btn.addEventListener(`click`, () => toggleShowClass(btn.closest(`.form`), btn.closest(`.form`).previousElementSibling));

    alertClose.addEventListener(`click`, event => toggleShowClass(null, event.target.closest(`.info__alert`)));

    mailClose.addEventListener(`click`, event => toggleShowClass(event.target.closest(`.form__mail`)));
};

document.addEventListener(`DOMContentLoaded`, loadEventListenners());