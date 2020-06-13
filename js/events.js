document.addEventListener(`DOMContentLoaded`, () => {
    const closeBtn = document.querySelectorAll(`.user__container .container__close`);
    const hamburgerMenu = document.querySelector(`.hamburger`);

    const loginButton = document.querySelector(`.login__button`);

    const handleHamburgerClick = (element) => {
        element.classList.toggle(`hamburger--active`);
        document.querySelector(`.main__nav`).classList.toggle(`main__nav--active`);
    }

    const handleCloseClick = (element) => {
        element.classList.toggle(`user__container--show`);
    }

    const handleLoginClick = (element) => {
        element.nextElementSibling.classList.toggle(`form__login--show`);
    }

    loginButton.addEventListener('click', () => {
        handleLoginClick(loginButton);
    });
    hamburgerMenu.addEventListener(`click`, () => handleHamburgerClick(hamburgerMenu));
    for(let btn of closeBtn) btn.addEventListener(`click`, () => handleCloseClick(btn.closest(`.user__container`)));
});