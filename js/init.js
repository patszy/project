document.addEventListener(`DOMContentLoaded`, () =>{
    //Validate forms
    const formLogin = document.getElementsByClassName(`form__login`)[0];
    const formRegister = document.getElementsByClassName(`form__register`)[0];
    const formMail = document.getElementsByClassName(`form__mail`)[0];
    const formRecovery = document.getElementsByClassName(`form__recovery`)[0];
    const formOptions = document.getElementsByClassName(`form__options`)[0];
    const postCreator = document.getElementsByClassName(`form__post`)[0];

    const formLoginValidation = new FormValidate(formLogin, {});
    const formRegisterValidation = new FormValidate(formRegister, {});
    const formMailValidation = new FormValidate(formMail, {});
    const formRecoveryValidation = new FormValidate(formRecovery, {});
    const formOptionsValidatio = new FormValidate(formOptions, {});
    const postCreatorValidation = new FormValidate(postCreator, {});

    //Load new users
    fetch(`./php/getNewUsers.php`, {method: `POST`})
        .then(response => response.json())
        .then(response => {
            if (response.status == `error`) this.toggleAlert(`${response.error}` , `error`, true);
            else {
                if (response.status == `warning`) this.toggleAlert(`${response.warning}` , `warning`, true);
                else if (response.status == `success`) loadNewUsers(response.users);
            }
        });

    //Load posts
    let loadPostsStatus = {rowNum: 0, rowCount: 5, searchStr: ``};

    if(searchPosts(loadPostsStatus)) searchPosts(loadPostsStatus).then(response => loadPostsStatus = response);
    loadPosts(loadPostsStatus).then(response => loadPostsStatus = response);

    //Check user cookies
    if(getCookie(`login`)) loadUserDataOnPage({id_user: getCookie(`id_user`), name: getCookie(`name`), email: getCookie(`email`), date: getCookie(`date`), city: getCookie(`city`), url_portrait: getCookie(`url_portrait`)});

    //Post animation
    let posts = document.querySelectorAll(`.post__container`);
    animatePosts(posts);

    //Event listenners
    window.addEventListener(`scroll`, () => { if(document.body.scrollHeight == window.scrollY+window.innerHeight) loadPosts(loadPostsStatus).then(response => loadPostsStatus.rowNum = response.rowNum) });
    window.addEventListener(`scroll`, () => animatePosts(posts));
});