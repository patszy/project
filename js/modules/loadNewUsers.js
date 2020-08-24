class NewUser{
    constructor(user) { this.user = user; }

    createElementDOM = (name, classes, text = ``, children, attributes) => {
        let element = document.createElement(name);
        element.innerText = text;

        if(classes) classes.forEach(className => element.classList.add(className));
        if(children) children.forEach(child => element.appendChild(child));
        if(attributes) attributes.forEach(attribute => element.setAttribute(attribute.name, attribute.value));

        return element;
    }

    addNewUserToDOM() {
        const parent = document.querySelector(`.new__users`);

        let userWrapper = this.createElementDOM(`div`, [`user__wrapper`], undefined, [
            this.createElementDOM(`div`, [`img__wrapper`], undefined, [
                this.createElementDOM(`div`, [`user__img`], undefined, undefined, [{name: `style`, value: `--url-portrait: url(${this.user.url_portrait});`}]),
            ]),
            this.createElementDOM(`span`, [`user__name`], this.user.login)
        ]);

        parent.appendChild(userWrapper);
    }
}

const loadNewUsers = (tabUsers) => {
    tabUsers.forEach(user => {
        let newUser = new NewUser(user);
        newUser.addNewUserToDOM();
    })
}

document.addEventListener(`DOMContentLoaded`, () => {
    fetch(`./php/getNewUsers.php`, {method: `POST`})
        .then(response => response.json())
        .then(response => {
            if (response.status == `error`) this.toggleAlert(`${response.error}` , `error`, true);
            else {
                if (response.status == `warning`) this.toggleAlert(`${response.warning}` , `warning`, true);
                else if (response.status == `success`) loadNewUsers(response.users);
            }
        });
});

