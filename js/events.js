document.addEventListener(`DOMContentLoaded`, () => {
    const closeContainerBtn = document.querySelectorAll(`.user__container .container__close`);
    const closeFormBtn = document.querySelectorAll(`.form__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.form input:not([type="submit"])`);
    const loginButton = document.querySelector(`.login__button`);
    const registerButton = document.querySelector(`.register__button`);
    const eyes = document.querySelectorAll(`.far[class*="fa-eye"]`);

    console.log(eyes);

    const handleHamburgerClick = (element) => {
        element.classList.toggle(`--show`);
        document.querySelector(`.main__nav`).classList.toggle(`--show`);
    }

    const handleCloseClick = (element) => {
        console.log(element);
        element.classList.toggle(`--show`);
    }

    const handleFormClick = (element) => {
        element.classList.toggle(`--show`);
        element.nextElementSibling.classList.toggle(`--show`);
    }

    // Add Event Listeners

    inputs.forEach(input => {
        input.addEventListener(`focus`, (event) => {
            event.target.closest('.form__row').classList.add(`focus`);
        });

        input.addEventListener(`blur`, (event) => {
            if(!event.target.value) event.target.closest('.form__row').classList.remove(`focus`);
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

    loginButton.addEventListener('click', () => handleFormClick(loginButton));
    registerButton.addEventListener('click', () => handleFormClick(registerButton));
    hamburgerMenu.addEventListener(`click`, () => handleHamburgerClick(hamburgerMenu));

    for(let closeBtn of closeContainerBtn) closeBtn.addEventListener(`click`, () => handleCloseClick(closeBtn.closest(`.user__container`)));

    for(let closeBtn of closeFormBtn) {
        closeBtn.addEventListener(`click`, () => {
            handleCloseClick(closeBtn.closest(`.form`));
            handleCloseClick(closeBtn.closest(`.form`).previousElementSibling);
        });
    }
});