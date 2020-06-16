document.addEventListener(`DOMContentLoaded`, () => {
    const closeBtn = document.querySelectorAll(`.user__container .container__close`);
    const closeFormBtn = document.querySelector(`.form__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);
    const inputs = document.querySelectorAll(`.form__login input:not([type="submit"])`);
    const loginButton = document.querySelector(`.login__button`);
    const eyes = document.querySelectorAll(`.form__row .far.fa-eye`);

    const handleHamburgerClick = (element) => {
        element.classList.toggle(`--show`);
        document.querySelector(`.main__nav`).classList.toggle(`--show`);
    }

    const handleCloseClick = (element) => {
        element.classList.toggle(`--show`);
    }

    const handleLoginClick = (element) => {
        element.classList.toggle(`--show`);
        element.nextElementSibling.classList.toggle(`--show`);
    }

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
            event.preventDefault();
            let input = event.target.previousElementSibling;
            (input.type == `password`) ? input.type = `text` : input.type = `password`;
        });
    });

    loginButton.addEventListener('click', () => handleLoginClick(loginButton));
    hamburgerMenu.addEventListener(`click`, () => handleHamburgerClick(hamburgerMenu));
    for(let btn of closeBtn) btn.addEventListener(`click`, () => handleCloseClick(btn.closest(`.user__container`)));
    closeFormBtn.addEventListener(`click`, () => {
        handleCloseClick(closeFormBtn.closest(`.form__login`));
        handleCloseClick(closeFormBtn.closest(`.form__login`).previousElementSibling);

    });
});