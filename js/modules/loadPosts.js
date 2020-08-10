class Post{
    constructor(user = null, date = null, title = null, category = null, content = null){
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
        if(attributes) attributes.forEach(attribute => element.setAttribute(attribute.name, attribute.value));

        return element;
    }

    addPostToDOM() {
        const parent = document.getElementsByClassName(`posts__wrapper`)[0];
        const loadButton = document.querySelector(`.btn__load`);

        let postContainer = this.createElementDOM(`section`, [`post__container`], undefined, [
            this.createElementDOM(`div`, [`post__data`], undefined, [
                this.createElementDOM(`div`, [`user__img`], undefined),
                this.createElementDOM(`div`, [`data__wrapper`], undefined, [
                    this.createElementDOM(`div`, [`user__data`], undefined, [
                        this.createElementDOM(`span`, [`user__name`], this.user.login),
                        this.createElementDOM(`span`, [`user__city`], this.user.city),
                        this.createElementDOM(`span`, [`user__age`], this.user.date)
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
            this.createElementDOM(`button`, [`btn__mail`], `Kontakt`, [this.createElementDOM(`i`, [`fas`, `fa-envelope`])], [{name: `mail`, value: this.user.email}])
        ]);

        // parent.append(postContainer);
        parent.insertBefore(postContainer, loadButton);
    }
}

const toggleAlert = (text, type, show) => {
    const infoAlert = document.querySelector(`.info__alert`);
    infoAlert.querySelector(`span`).innerText = text;
    infoAlert.classList.remove(`--show`, `--error`, `--warning`,  `--success`, `--info`);
    show ? infoAlert.classList.add(`--show`, `--${type}`) : false;
}

const createPosts = (tabPosts) => {
    tabPosts.forEach(post => {
        post.user.date = new Date().getFullYear() - post.user.date;
        let newPost = new Post(post.user, post.postDate, post.title, post.category, post.content);
        newPost.addPostToDOM();

        let posts = document.querySelectorAll(`.post__container`);
        animatePosts(posts);
        window.addEventListener(`scroll`, () => animatePosts(posts));
    })
}

const refreshPostEvents = () => {
    let toggleShowClass = (element, parent = null) => {
        if(element) element.classList.toggle(`--show`);
        if(parent) parent.classList.toggle(`--show`);
    }

    let emailButtons = document.querySelectorAll(`.btn__mail`);

    emailButtons.forEach(btn => btn.addEventListener(`click`, () => {
        toggleShowClass(document.querySelector(`.form__mail`),  document.querySelector(`.mail__creator`));
        document.getElementById(`mail__recipient`).setAttribute(`value`, btn.getAttribute(`mail`));
        document.getElementById(`mail__login`).value = btn.parentElement.querySelector(`.user__name`).innerText;
    }));
}

const loadPosts = (rowNum = 0, rowCount = 1, data, removePosts) => {
    const formData = data || new FormData();

    if(typeof(rowNum) != undefined) formData.append(`rowNum`, rowNum);
    if(typeof(rowCount) != undefined) formData.append(`rowCount`, rowCount);

    for (var value of formData.entries()) console.log(value);

    fetch(`./php/getPostsData.php`, {
        method: `POST`,
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        console.log(response);
        if (response.status == `error`) toggleAlert(`${response.error}` , `error`, true);
        else {
            if (response.status == `warning`) toggleAlert(`${response.warning}` , `warning`, true);
            else if (response.status == `success`) {
                createPosts(response.posts);
                refreshPostEvents();
            }
        }
    });

    return rowNum += rowCount;
}

const searchPosts = (rowNum, rowCount) => {
    const formBar = document.querySelector(`.search__bar`);

    formBar.addEventListener(`submit`, event => {
        event.preventDefault();

        const submit = formBar.querySelector(`.submit`);
        submit.disabled = true;
        submit.classList.add(`loading`);

        const formData = new FormData(formBar);

        rowNum = loadPosts(rowNum, rowCount, formData, true);
        submit.disabled = false;
        submit.classList.remove(`loading`);
    });

    return rowNum;
}

document.addEventListener(`DOMContentLoaded`, () => {
    let rowNum = 0;
    const rowCount = 5;

    // rowNum = searchPosts(rowNum, rowCount);
    loadPosts(rowNum, rowCount);

    window.addEventListener(`scroll`, () => { if(document.body.scrollHeight == window.scrollY+window.innerHeight) rowNum = loadPosts(rowNum, rowCount) });

});

