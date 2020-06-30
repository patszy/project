class Post{
    constructor(author = `admin`, title = null, content = null){
        this.author = author;
        this.title = this.getInputValue(title);
        this.content = this.getInputValue(content);
        this.time = this.createPostDate();
    }

    getInputValue = input => {if(input) return input.value};

    clearInputValue = (tab) => {if(tab) tab.forEach(item => item.value = ``)};

    createPostDate = () => new Date().toLocaleString();

    createElementDOM = (name, classes = null, text = ``, children = null) => {
        let element = document.createElement(name);
        element.innerText = text;

        if(classes) classes.forEach(className => element.classList.add(className));
        if(children) children.forEach(child => element.appendChild(child));

        return element;
    }

    addPostToDOM() {
        const main = document.getElementsByTagName(`main`)[0];

        let userContainer = this.createElementDOM(`section`, [`user__container`], undefined, [
            this.createElementDOM(`h1`, [`user__data`], undefined, [
                this.createElementDOM(`div`, [`user__name`], `Admin 123`),
                this.createElementDOM(`div`, [`user__city`], `Warszawa`),
                this.createElementDOM(`div`, [`user__age`], `18`)
            ]),
            this.createElementDOM(`div`, [`container__mail`], undefined, [this.createElementDOM(`i`, [`far`, `fa-envelope`])]),
            this.createElementDOM(`div`, [`container__close`]),
            this.createElementDOM(`article`, [`user__article`], undefined, [
                this.createElementDOM(`div`, [`article__data`], undefined, [
                    this.createElementDOM(`time`, undefined, this.time),
                    this.createElementDOM(`h2`, undefined, this.title)
                ]),
                this.createElementDOM(`p`, [`article__content`], this.content)
            ])
        ]);

        main.appendChild(userContainer);
    }
}

// document.addEventListener(`DOMContentLoaded`, () => {
//     const formPost = document.querySelector(`.form__post`);
//     formPost.addEventListener(`submit`, event => {
//         event.preventDefault();

//         let title = document.getElementById(`title`);
//         let content = document.getElementById(`content`);

//         const post = new Post(undefined, title, content);
//         post.addPostToDOM();
//         post.clearInputValue([title, content]);

//         formPost.classList.toggle(`--show`);
//         formPost.previousElementSibling.classList.toggle(`--show`);
//     });
// });

