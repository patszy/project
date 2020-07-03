class Post{
    constructor(user = {name: `Admin123`, city: `Warszawa`, age: 18}, title = null, content = null){
        this.user = user;
        this.title = this.getInputValue(title);
        this.content = this.getInputValue(content);
        this.time = this.createPostDate();
    }

    getInputValue = input => {if(input) return input.value};

    clearInputValue = (tab) => {if(tab) tab.forEach(item => item.value = ``)};

    createPostDate = () => new Date().toLocaleString();

    createElementDOM = (name, classes, text = ``, children) => {
        let element = document.createElement(name);
        element.innerText = text;

        if(classes) classes.forEach(className => element.classList.add(className));
        if(children) children.forEach(child => element.appendChild(child));

        return element;
    }

    addPostToDOM() {
        const main = document.getElementsByTagName(`main`)[0];

        let userContainer = this.createElementDOM(`section`, [`post__container`], undefined, [
            this.createElementDOM(`div`, [`post__data`], undefined, [
                this.createElementDOM(`div`, [`user__img`], undefined),
                this.createElementDOM(`div`, [`data__wrapper`], undefined, [
                    this.createElementDOM(`div`, [`user__data`], undefined, [
                        this.createElementDOM(`span`, [`user__name`], this.user.name),
                        this.createElementDOM(`span`, [`user__city`], this.user.city),
                        this.createElementDOM(`span`, [`user__age`], this.user.age)
                    ]),
                    this.createElementDOM(`div`, [`article__data`], undefined, [
                        this.createElementDOM(`time`, undefined, this.time),
                        this.createElementDOM(`h2`, undefined, this.title)
                    ]),
                ]),
            ]),
            this.createElementDOM(`article`, [`post__content`], undefined, [
                this.createElementDOM(`p`, [`clearfix`], this.content)
            ]),
            this.createElementDOM(`button`, [`container__mail`], `Kontakt`, [this.createElementDOM(`i`, [`fas`, `fa-envelope`])])
        ]);

        main.appendChild(userContainer);
    }
}

document.addEventListener(`DOMContentLoaded`, () => {
    const formPost = document.querySelector(`.form__post`);
    formPost.addEventListener(`submit`, event => {
        event.preventDefault();

        let title = document.getElementById(`title`);
        let content = document.getElementById(`content`);

        const post = new Post(undefined, title, content);

        post.addPostToDOM();
        post.clearInputValue([title, content]);
    });
});

