const createPosts = (tabPosts) => {
    tabPosts.forEach(post => {
        post.user.date = new Date().getFullYear() - post.user.date;
        let newPost = new Post(post.user, post.id_post, post.postDate, post.title, post.category, post.content, post.url_post_img);
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
        document.querySelector(`.form__mail [name="email_recipient"]`).setAttribute(`value`, btn.getAttribute(`mail`));
        document.querySelector(`.form__mail [name="login"]`).value = btn.parentElement.querySelector(`.user__name`).innerText;
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

const animatePosts = (posts) => {
    posts.forEach(post => {
        if(post.getBoundingClientRect().top <= window.screen.height-300) {
            post.classList.add(`--load`);
        } else post.classList.remove(`--load`);
    });
}