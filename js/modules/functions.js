const loadUserDataOnPage = (sessionUserData) =>{
    //PostCreator
    document.querySelector(`.post__creator`).style.display = `block`;
    document.querySelector(`.post__creator input[name="user__id"]`).value = sessionUserData.id_user;
    document.querySelector(`.post__creator .user__name`).innerText = sessionUserData.name;
    document.querySelector(`.post__creator .user__city`).innerText = sessionUserData.city;
    document.querySelector(`.post__creator .user__age`).innerText = new Date().getFullYear() - sessionUserData.date;
    document.querySelector(`.post__creator .user__img`).setAttribute(`style`, `--url-portrait: url(.${sessionUserData.url_portrait})`);
    //Contact
    document.querySelector(`.form__mail .mail__recipient`).value = sessionUserData.email;
    document.querySelector(`.form__mail .mail__recipient`).parentElement.classList.add(`--focus`);
    //Menu settings
    toggleShowClass(document.querySelector(`.user__bar li:nth-child(1)`));
    toggleShowClass(document.querySelector(`.user__bar li:nth-child(2)`));
    toggleShowClass(document.querySelector(`.user__bar li:nth-child(3)`));
    userUserIdOpt = document.querySelector(`.form__options [name="user_id"]`).value = sessionUserData.id_user;
    userLoginOpt = document.querySelector(`.form__options [name="login"]`).value = sessionUserData.name;
    userEmailOpt = document.querySelector(`.form__options [name="email"]`).value = sessionUserData.email;
    //Error solution: no warszawa in options.
    userCityOpt = document.querySelector(`.form__options [value=${sessionUserData.city.toLowerCase()}]`).selected = true;
    //Delete post form
    showDeletePostFormBtn();
    setDeletePostFormUserId();
}

const loadNewUsers = (tabUsers) => {
    tabUsers.forEach(user => {
        let newUser = new NewUser(user);
        newUser.addNewUserToDOM();
    })
}

const toggleAlert = (text, type, show) => {
    const infoAlert = document.querySelector(`.info__alert`);
    infoAlert.querySelector(`span`).innerText = text;
    infoAlert.classList.remove(`--show`, `--error`, `--warning`,  `--success`, `--info`);
    show ? infoAlert.classList.add(`--show`, `--${type}`) : false;
}

const toggleShowClass = (element, parent = null) => {
    if(element) element.classList.toggle(`--show`);
    if(parent) parent.classList.toggle(`--show`);
}

const toggleFocusClass = (element, show) => {
    show ? element.classList.add(`--focus`) : element.classList.remove(`--focus`);
};

const logIn = (data) => {
    for (const [key, value] of Object.entries(data)) setCookie(`${key}`, `${value}`);
    loadUserDataOnPage(data);
}

const logOut = () => {
    removeCookie(`login`);
    removeCookie(`id_user`);
    removeCookie(`name`);
    removeCookie(`email`);
    removeCookie(`date`);
    removeCookie(`city`);
    removeCookie(`permission`);
    removeCookie(`url_portrait`);
}

const createElementDOM = (name, classes, text = ``, children, attributes) => {
    let element = document.createElement(name);
    element.innerText = text;

    if(classes) classes.forEach(className => element.classList.add(className));
    if(children) children.forEach(child => element.appendChild(child));
    if(attributes) attributes.forEach(attribute => element.setAttribute(attribute.name, attribute.value));

    return element;
}