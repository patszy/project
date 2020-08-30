class Post{
    constructor(user = null, id_post, date = null, title = null, category = null, content = null, url_post_img = null){
        this.user = user;
        this.id_post = id_post;
        this.date = date || this.createPostDate();
        this.title = title || this.getInputValue(title);
        this.category =  category || this.getInputValue(category);
        this.content = content || this.getInputValue(content);
        this.url_post_img = url_post_img;
    }

    getInputValue = input => {if(input) return input.value};

    clearInputValue = (tab) => {if(tab) tab.forEach(item => item.value = ``)};

    createPostDate = () => new Date().toLocaleString();

    addPostToDOM(before = false) {
        const parent = document.getElementsByClassName(`posts__wrapper`)[0];
        let postImgAttr;
        this.url_post_img ? postImgAttr = [{name: `src`, value: this.url_post_img}] : postImgAttr = undefined;

        let postContainer = createElementDOM(`section`, [`post__container`], undefined, [
            createElementDOM(`div`, [`post__data`], undefined, [
                createElementDOM(`img`, [`user__img`], undefined, undefined, [{name: `src`, value: this.user.url_portrait}]),
                createElementDOM(`div`, [`data__wrapper`], undefined, [
                    createElementDOM(`div`, [`user__data`], undefined, [
                        createElementDOM(`span`, [`user__name`], this.user.login),
                        createElementDOM(`span`, [`user__city`], this.user.city),
                        createElementDOM(`span`, [`user__age`], this.user.date)
                    ]),
                    createElementDOM(`div`, [`article__data`], undefined, [
                        createElementDOM(`time`, undefined, this.date),
                        createElementDOM(`h2`, undefined, this.title),
                        createElementDOM(`span`, [`article__category`], this.category)
                    ]),
                ]),
            ]),
            createElementDOM(`article`, [`post__content`], undefined, [
                createElementDOM(`p`, [`clearfix`], this.content)
            ]),
            createElementDOM(`img`, [`post__img`], undefined, undefined, postImgAttr),
            createElementDOM(`form`, [`form__delete__post`], undefined, [
                createElementDOM(`input`, [`input`], undefined, [], [{name: `type`, value: `text`}, {name: `name`, value: `post_id`}, {name: `required`, value: ``}, {name: `readonly`, value: ``}, {name: `value`, value: this.id_post}]),
                createElementDOM(`input`, [`input`], undefined, [], [{name: `type`, value: `text`}, {name: `name`, value: `user_id`}, {name: `required`, value: ``}, {name: `readonly`, value: ``}, {name: `value`, value: (getCookie('id_user')!=undefined)?getCookie('id_user'):``}]),
                createElementDOM(`label`, [`guardian`], undefined, [
                    createElementDOM(`input`, undefined, undefined, undefined, [{name: `name`, value: `guardian`}, {name: `type`, value: `checkbox`}])
                ]),
                createElementDOM(`button`, [`btn__delete`, `form__submit`], `Usuń Post`, [createElementDOM(`i`, [`fas`, `fa-trash-alt`])], undefined)
            ], [{name: `action`, value: `./php/modules/deletePost.php`}, {name: `method`, value: `POST`}]),
            createElementDOM(`button`, [`btn__mail`], `Kontakt`, [createElementDOM(`i`, [`fas`, `fa-envelope`])], [{name: `mail`, value: this.user.email}])
        ]);

        if(before) parent.prepend(postContainer);
        else parent.append(postContainer);
    }
}