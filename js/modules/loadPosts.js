class Post{
    constructor(user = null, id_post, date = null, title = null, category = null, content = null){
        this.user = user;
        this.id_post = id_post;
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
                this.createElementDOM(`div`, [`user__img`], undefined, undefined, [{name: `style`, value: `--url-portrait: url(${this.user.url_portrait});`}]),
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
            this.createElementDOM(`form`, [`form__delete__post`], undefined, [
                this.createElementDOM(`input`, [`input`], undefined, [], [{name: `type`, value: `text`}, {name: `name`, value: `post_id`}, {name: `required`, value: ``}, {name: `readonly`, value: ``}, {name: `value`, value: this.id_post}]),
                this.createElementDOM(`input`, [`input`], undefined, [], [{name: `type`, value: `text`}, {name: `name`, value: `user_id`}, {name: `required`, value: ``}, {name: `readonly`, value: ``}, {name: `value`, value: (getCookie('id_user')!=undefined)?getCookie('id_user'):``}]),
                this.createElementDOM(`label`, [`guardian`], undefined, [
                    this.createElementDOM(`input`, undefined, undefined, undefined, [{name: `name`, value: `guardian`}, {name: `type`, value: `checkbox`}])
                ]),
                this.createElementDOM(`button`, [`btn__delete`], `Usuń Post`, [this.createElementDOM(`i`, [`fas`, `fa-trash-alt`])], undefined)
            ], [{name: `action`, value: `./php/deletePost.php`}, {name: `method`, value: `POST`}]),
            this.createElementDOM(`button`, [`btn__mail`], `Kontakt`, [this.createElementDOM(`i`, [`fas`, `fa-envelope`])], [{name: `mail`, value: this.user.email}])
        ]);

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
        let newPost = new Post(post.user, post.id_post, post.postDate, post.title, post.category, post.content);
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

const loadPosts = (Status) => {
    const formData = new FormData();

    if(typeof(Status.rowNum) != undefined) formData.append(`rowNum`, Status.rowNum);
    if(typeof(Status.rowCount) != undefined) formData.append(`rowCount`, Status.rowCount);
    if(typeof(Status.searchStr) != undefined) formData.append(`searchStr`, Status.searchStr);

    console.log(Status);
    for (var value of formData.entries()) console.log(value);

    return fetch(`./php/getPostsData.php`, {
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
                Status.rowNum += Status.rowCount;
                showDeletePostFormBtn();
                setDeletePostFormUserId();
            }
        }

        return Status;
    })
}

const searchPosts = (Status) => {
    const formBar = document.querySelector(`.search__bar`);

    formBar.addEventListener(`submit`, event => {
        event.preventDefault();

        const submit = formBar.querySelector(`.submit`);
        submit.disabled = true;
        submit.classList.add(`loading`);

        const formData = new FormData(formBar);

        Status.searchStr = formData.get(`searchStr`);
        Status.rowNum = 0
        document.querySelector(`.posts__wrapper`).innerHTML = ``;

        return loadPosts(Status).finally(() => {
                submit.disabled = false;
                submit.classList.remove(`loading`);
                return Status;
            });
    });
}

const showDeletePostFormBtn = () => {
    document.querySelectorAll(`.post__container`).forEach(post => {
        if(getCookie(`name`) == post.querySelector(`.user__name`).innerText || getCookie(`permission`) == 1) post.querySelector(`.form__delete__post`).classList.add(`--show`);
    })
}

const setDeletePostFormUserId = () => {
    document.querySelectorAll(`.post__container`).forEach(post => {
        if(getCookie(`id_user`)) post.querySelector(`input[name="user_id"]`).value = getCookie(`id_user`);
    })
}

document.addEventListener(`DOMContentLoaded`, () =>{
    let loadPostsStatus = {rowNum: 0, rowCount: 5, searchStr: ``};

    if(searchPosts(loadPostsStatus)) searchPosts(loadPostsStatus).then(response => loadPostsStatus = response);

    loadPosts(loadPostsStatus).then(response => loadPostsStatus = response);

    window.addEventListener(`scroll`, () => { if(document.body.scrollHeight == window.scrollY+window.innerHeight) loadPosts(loadPostsStatus).then(response => loadPostsStatus.rowNum = response.rowNum) });

    if(getCookie(`login`)) loadUserDataOnPage({id_user: getCookie(`id_user`), name: getCookie(`name`), email: getCookie(`email`), date: getCookie(`date`), city: getCookie(`city`)});
});