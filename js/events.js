document.addEventListener(`DOMContentLoaded`, () => {
    const closeContainerBtn = document.querySelectorAll(`.user__container .container__close`);
    const closeFormBtn = document.querySelectorAll(`.form__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.form .input`);
    const loginButton = document.querySelector(`.login__button`);
    const registerButton = document.querySelector(`.register__button`);
    const postButton = document.querySelector(`.post__button`);
    const eyes = document.querySelectorAll(`.far[class*="fa-eye"]`);

    const toggleShowClass = (element, parent = null) => {
        element.classList.toggle(`--show`);
        if(parent) parent.classList.toggle(`--show`);
    }

    const toggleFocusClass = (element, show) => {
        show ? element.classList.add(`--focus`) : element.classList.remove(`--focus`);
    };

    // Add Event Listeners

    inputs.forEach(input => {
        input.addEventListener(`focus`, event => {
            toggleFocusClass(event.target.closest('.form__row'), true);
        });
        input.addEventListener(`blur`, event => {
            (!event.target.value) ? toggleFocusClass(event.target.closest('.form__row'), false) : null;
        });
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

    loginButton.addEventListener('click', () => toggleShowClass(loginButton, loginButton.nextElementSibling));
    registerButton.addEventListener('click', () => toggleShowClass(registerButton, registerButton.nextElementSibling));
    postButton.addEventListener('click', () => toggleShowClass(postButton, postButton.nextElementSibling));
    hamburgerMenu.addEventListener(`click`, () => toggleShowClass(hamburgerMenu, document.querySelector(`.main__nav`)));

    for(let closeBtn of closeContainerBtn) closeBtn.addEventListener(`click`, () => toggleShowClass(closeBtn.closest(`.user__container`)));
    for(let closeBtn of closeFormBtn) closeBtn.addEventListener(`click`, () => toggleShowClass(closeBtn.closest(`.form`), closeBtn.closest(`.form`).previousElementSibling));
});