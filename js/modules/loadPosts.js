class Post{
    constructor(user = {name: `Admin123`, city: `Warszawa`, age: 18}, date = null, title = null, category = null, content = null){
        this.user = user;
        this.date = date || this.createPostDate();
        this.title = title || this.getInputValue(title);
        this.category =  category || this.getInputValue(category);
        this.content = content || this.getInputValue(content);
    }

    getInputValue = input => {if(input) return input.value};

    clearInputValue = (tab) => {if(tab) tab.forEach(item => item.value = ``)};

    createPostDate = () => new Date().toLocaleString();

    createElementDOM = (name, classes, text = ``, children, attributes) => {
        let element = document.createElement(name);
        element.innerText = text;

        if(classes) classes.forEach(className => element.classList.add(className));
        if(children) children.forEach(child => element.appendChild(child));
        // if(attributes) attributes.forEach(attribute => element.setAttribute(attribute.name, attribute.value));
        if(attributes) attributes.forEach(attribute => element.setAttribute(attribute.name, attribute.value));

        return element;
    }

    addPostToDOM() {
        const parent = document.getElementsByTagName(`main`)[0];
        const actualNode = document.getElementsByClassName(`post__container`)[0];

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
                        this.createElementDOM(`time`, undefined, this.date),
                        this.createElementDOM(`h2`, undefined, this.title),
                        this.createElementDOM(`span`, [`article__category`], this.category)
                    ]),
                ]),
            ]),
            this.createElementDOM(`article`, [`post__content`], undefined, [
                this.createElementDOM(`p`, [`clearfix`], this.content)
            ]),
            this.createElementDOM(`button`, [`btn__mail`], `Kontakt`, [this.createElementDOM(`i`, [`fas`, `fa-envelope`])], [{name: `mail`, value: `patszy97@interia.pl`}])
        ]);

        parent.insertBefore(userContainer, actualNode);
    }
}

let tabPosts;

const toggleAlert = (text, type, show) => {
    const infoAlert = document.querySelector(`.info__alert`);
    infoAlert.querySelector(`span`).innerText = text;
    infoAlert.classList.remove(`--show`, `--error`, `--warning`,  `--success`, `--info`);
    show ? infoAlert.classList.add(`--show`, `--${type}`) : false;
}

const loadPosts = (tabPosts) => {
    tabPosts.forEach(post => {
        let newPost = new Post(undefined, post.date, post.title, post.category, post.content);
        newPost.addPostToDOM();

        let posts = document.querySelectorAll(`.post__container`);
        animatePosts(posts);
        window.addEventListener(`scroll`, () => animatePosts(posts));
    })
}

const refreshEvents = () => {
    let toggleShowClass = (element, parent = null) => {
        if(element) element.classList.toggle(`--show`);
        if(parent) parent.classList.toggle(`--show`);
    }

    let emailButtons = document.querySelectorAll(`.btn__mail`);

    emailButtons.forEach(btn => btn.addEventListener(`click`, () => {
        toggleShowClass(document.querySelector(`.form__mail`),  document.querySelector(`.mail__creator`));
        document.getElementById(`mail__recipient`).setAttribute(`value`, btn.getAttribute(`mail`));
    }));
}

document.addEventListener(`DOMContentLoaded`, () => {
    fetch(`./php/getPostsData.php`, {method: `POST`})
        .then(response => response.json())
        .then(response => {
            if (response.status == `error`) this.toggleAlert(`${response.error}` , `error`, true);
            else {
                if (response.status == `warning`) this.toggleAlert(`${response.warning}` , `warning`, true);
                else if (response.status == `success`) {
                    loadPosts(response.posts);
                    refreshEvents();
                }
            }
        });

    //Submitting Form

    /*  const formPost = document.querySelector(`.form__post`);
        formPost.addEventListener(`submit`, event => {
        event.preventDefault();


        let title = document.getElementById(`post__title`);
        let content = document.getElementById(`post__content`);

        const post = new Post(undefined, title, content);

        post.addPostToDOM();
        post.clearInputValue([title, content]);

        let posts = document.querySelectorAll(`.post__container`);

        animatePosts(posts);

        window.addEventListener(`scroll`, () => animatePosts(posts));
    }); */
});
