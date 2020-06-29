class Post{
    constructor(author = `admin`, title = ``, content = ``){
        this.author = author;
        this.title = this.getTitle();
        this.content = this.getContent();
        this.time = this.createPostDate();
    }

    getTitle = () => document.getElementById(`title`).value;

    getContent = () => document.getElementById(`content`).value;

    createPostDate = () => new Date().toLocaleString();

    addPostToDOM() {
        const main = document.getElementsByTagName(`main`)[0];

        let userContainer = createElementDOM(`section`, [`user__container`], undefined, [
            createElementDOM(`h1`, [`user__data`], undefined, [
                createElementDOM(`div`, [`user__name`], `Admin 123`),
                createElementDOM(`div`, [`user__city`], `Warszawa`),
                createElementDOM(`div`, [`user__age`], `18`)
            ]),
            createElementDOM(`div`, [`container__mail`], undefined, [createElementDOM(`i`, [`far`, `fa-envelope`])]),
            createElementDOM(`div`, [`container__close`]),
            createElementDOM(`article`, [`user__article`], undefined, [
                createElementDOM(`div`, [`article__data`], undefined, [
                    createElementDOM(`time`, undefined, this.time),
                    createElementDOM(`h2`, undefined, this.title)
                ]),
                createElementDOM(`p`, [`article__content`], this.content)
            ])
        ]);

        main.appendChild(userContainer);
    }
}

const createElementDOM = (name, classes = null, text = ``, children = null) => {
    let element = document.createElement(name);
    element.innerText = text;

    if(classes) classes.forEach(className => element.classList.add(className));
    if(children) children.forEach(child => element.appendChild(child));

    return element;
}

document.addEventListener(`DOMContentLoaded`, () => {
    document.querySelector(`.form__post`).addEventListener(`submit`, event => {
        event.preventDefault();

        const post = new Post();
        post.addPostToDOM();
    });
});

