const loadEventListenners = () => {
    const togglePostCreatorBtn = document.querySelector(`.post__creator .btn__close`);
    const closeFormBtns = document.querySelectorAll(`.form__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.input`);
    const menuBtns = document.querySelectorAll(`[class$="__button"]`);
    const eyes = document.querySelectorAll(`.far[class*="fa-eye"]`);
    const time = document.querySelectorAll(`.post__creator time`)[0];
    const infoAlert = document.querySelector(`.info__alert`);

    const toggleShowClass = (element, parent = null) => {
        element.classList.toggle(`--show`);
        if(parent) parent.classList.toggle(`--show`);
    }

    const toggleFocusClass = (element, show) => {
        show ? element.classList.add(`--focus`) : element.classList.remove(`--focus`);
    };

    const timeInterval = window.setInterval(() => time.innerText = new Date().toLocaleString(), 1000);

    // Add Event Listeners

    inputs.forEach(input => {
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

    hamburgerMenu.addEventListener(`click`, () => toggleShowClass(hamburgerMenu, document.querySelector(`.main__nav`)));

    togglePostCreatorBtn.addEventListener(`click`, () => toggleShowClass(togglePostCreatorBtn.closest(`.post__creator`)));
    for(let closeBtn of closeFormBtns) closeBtn.addEventListener(`click`, () => toggleShowClass(closeBtn.closest(`.form`), closeBtn.closest(`.form`).previousElementSibling));
};

document.addEventListener(`DOMContentLoaded`, loadEventListenners());