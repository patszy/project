document.addEventListener(`DOMContentLoaded`, () =>{
    //Validate forms
    const formLogin = document.getElementsByClassName(`form__login`)[0];
    const formRegister = document.getElementsByClassName(`form__register`)[0];
    const formMail = document.getElementsByClassName(`form__mail`)[0];
    const formRecovery = document.getElementsByClassName(`form__recovery`)[0];
    const formOptions = document.getElementsByClassName(`form__options`)[0];
    const formPostCreator = document.getElementsByClassName(`form__post`)[0];

    const formLoginValidation = new FormValidate(formLogin, {});
    const formRegisterValidation = new FormValidate(formRegister, {});
    const formMailValidation = new FormValidate(formMail, {});
    const formRecoveryValidation = new FormValidate(formRecovery, {});
    const formOptionsValidatio = new FormValidate(formOptions, {});
    const formPostCreatorValidation = new FormValidate(formPostCreator, {});

    //Load new users
    fetch(`./php/modules/getNewUsers.php`, {method: `POST`})
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

    //Event listenners
    window.addEventListener(`scroll`, () => { if(document.body.scrollHeight <= window.scrollY+window.innerHeight) loadPosts(loadPostsStatus).then(response => loadPostsStatus.rowNum = response.rowNum) });

    // Category menu
    document.querySelectorAll(`.category__nav li[value]`).forEach(item => {
        item.addEventListener(`click`, () => {
            document.querySelector(`.search__bar input[name="searchStr"]`).value = item.getAttribute(`value`);
            document.querySelector(`.posts__wrapper`).innerHTML = ``;

            loadPostsStatus = ({rowNum: 0, rowCount: 5, searchStr: item.getAttribute(`value`)});
            loadPosts(loadPostsStatus).then(response => loadPostsStatus = response);
        })
    })
});