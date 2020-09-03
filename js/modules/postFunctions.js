const createPosts = (tabPosts, before = false) => {
    tabPosts.forEach(post => {
        post.user.date = new Date().getFullYear() - post.user.date;
        let newPost = new Post(post.user, post.id_post, post.postDate, post.title, post.category, post.content, post.url_post_img);
        newPost.addPostToDOM(before);

        let addedPost;

        if(before) addedPost = document.querySelector(`.post__container`);
        else addedPost = document.querySelectorAll(`.post__container`)[document.querySelectorAll(`.post__container`).length-1];

        animatePosts([addedPost]);
        window.addEventListener(`scroll`, () => animatePosts([addedPost]));
        addPostMailEvent(addedPost);
        showDeletePostFormBtn([addedPost]);
        setDeletePostFormUserId([addedPost]);
    })
}

const addPostMailEvent = post => {
    if(post) {
        let emailButton = post.querySelector(`.btn__mail`);

        emailButton.addEventListener(`click`, () => {
            toggleShowClass(document.querySelector(`.form__mail`));
            document.querySelector(`.form__mail [name="email_recipient"]`).setAttribute(`value`, emailButton.getAttribute(`email`));
            document.querySelector(`.form__mail [name="login"]`).value = emailButton.parentElement.querySelector(`.user__name`).innerText;
            document.querySelector(`.form__mail [name="title"]`).value = `Odp: ${emailButton.parentElement.querySelector(`.article__data h2`).innerText}`;
        });
    }
}

const loadPosts = (Status) => {
    const formData = new FormData();

    if(typeof(Status.rowNum) != undefined) formData.append(`rowNum`, Status.rowNum);
    if(typeof(Status.rowCount) != undefined) formData.append(`rowCount`, Status.rowCount);
    if(typeof(Status.searchStr) != undefined) formData.append(`searchStr`, Status.searchStr);

    return fetch(`./php/modules/getPostsData.php`, {
        method: `POST`,
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        if (response.status == `error`) toggleAlert(`${response.error}` , `error`, true);
        else {
            if (response.status == `warning`) toggleAlert(`${response.warning}` , `warning`, true);
            else if (response.status == `success`) {
                createPosts(response.posts);
                Status.rowNum += Status.rowCount;
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

const showDeletePostFormBtn = posts => {
    if(posts) {
        posts.forEach(post => {
            if(post && getCookie(`name`) == post.querySelector(`.user__name`).innerText || getCookie(`permission`) == 1) {
                let form = post.querySelector(`.form__delete__post`).classList.add(`--show`);

                post.querySelector(`.form__delete__post .btn__delete`).addEventListener(`click`, event => {
                    let formPostDelete = event.target.parentElement;
                    let formPostDeleteValidation = new FormValidate(formPostDelete, {});
                });
            }
        });
    }
}

const setDeletePostFormUserId = posts => {
    if(posts) posts.forEach(post => { if(post && getCookie(`id_user`)) post.querySelector(`input[name="user_id"]`).value = getCookie(`id_user`) });
}

const animatePosts = (posts) => {
    posts.forEach(post => {
        if(post.getBoundingClientRect().top <= window.screen.height-300) {
            post.classList.add(`--load`);
        } else post.classList.remove(`--load`);
    });
}