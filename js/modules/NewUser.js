class NewUser{
    constructor(user) { this.user = user; }

    addNewUserToDOM() {
        const parent = document.querySelector(`.new__users`);

        let userWrapper = createElementDOM(`div`, [`user__wrapper`], undefined, [
            createElementDOM(`div`, [`img__wrapper`], undefined, [
                createElementDOM(`div`, [`user__img`], undefined, undefined, [{name: `style`, value: `--url-portrait: url(.${this.user.url_portrait});`}]),
            ]),
            createElementDOM(`span`, [`user__name`], this.user.login)
        ]);

        parent.appendChild(userWrapper);
    }
}