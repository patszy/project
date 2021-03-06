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
        addPostCautionEvent(addedPost);
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

const addPostCautionEvent = post => {
    if(post) {
        let cautionButton = post.querySelector(`.btn__caution`);

        cautionButton.addEventListener(`click`, () => {
            toggleShowClass(document.querySelector(`.form__mail`));
            document.querySelector(`.form__mail [name="email_recipient"]`).setAttribute(`value`, `gejusz.pl@gmail.com`);
            document.querySelector(`.form__mail [name="login"]`).value = `Administrator`;
            document.querySelector(`.form__mail [name="title"]`).value = `Zastrzeżenie do postu id:${cautionButton.getAttribute(`id_post`)}.`;
        });
    }
}

const loadPosts = (Status) => {
    const formData = new FormData();

    if(typeof(Status.rowNum) != undefined) formData.append(`rowNum`, Status.rowNum);
    if(typeof(Status.rowCount) != undefined) formData.append(`rowCount`, Status.rowCount);
    if(typeof(Status.searchStr) != undefined) formData.append(`searchStr`, Status.searchStr);
    if(Status.filterData) for (const [key, value] of Object.entries(Status.filterData)) formData.append(`${key}`, value);

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

        Status.filterData = {};
        Status.searchStr = formData.get(`searchStr`);
        Status.rowNum = 0;
        document.querySelector(`.posts__wrapper`).innerHTML = ``;

        return loadPosts(Status).finally(() => {
                submit.disabled = false;
                submit.classList.remove(`loading`);
                return Status;
            });
    });
}

const filterPosts = (Status) => {
    let formFilter = document.querySelector(`.form__filter`);

    formFilter.addEventListener(`submit`, event => {
        event.preventDefault();

        const submit = formFilter.querySelector(`.submit`);
        submit.disabled = true;
        submit.classList.add(`loading`);

        const formData = new FormData(formFilter);

        Status.searchStr = ``;
        Status.filterData = {};
        if(formData.get("post_id") != ``) Status.filterData.postId = formData.get("post_id");
        if(formData.get("login") != ``) Status.filterData.login = formData.get("login");
        if(formData.get("city") != ``) Status.filterData.city = formData.get("city");
        if(formData.get("date_from") != ``) Status.filterData.dateFrom = formData.get("date_from");
        if(formData.get("date_to") != ``) Status.filterData.dateTo = formData.get("date_to");
        if(formData.get("title") != ``) Status.filterData.title = formData.get("title");
        if(formData.get("category") != ``) Status.filterData.category = formData.get("category");
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