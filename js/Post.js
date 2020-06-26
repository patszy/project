class Post{
    constructor(author = `admin`, title, content){
        this.author = author;
        this.title = this.getTitle();
        this.content = this.getContent();
        this.time = this.getDate();
    }

    getTitle = () => {
        return document.getElementById(`title`).value;
    }

    getContent = () => {
        return document.getElementById(`content`).value;
    }

    getDate = () => {
        const today = new Date();
        return today.toLocaleString();
    }

    addPost() {
        const main = document.getElementsByTagName(`main`)[0];

        let userContainer = document.createElement(`section`);
        let userData = document.createElement(`h1`);
        let userDatas;
        let userArticle = document.createElement(`article`);

        userContainer.classList.add(`user__container`);
        userData.classList.add(`user__data`);
        userArticle.classList.add(`user__article`);

        userDatas = document.createElement(`div`);
        userDatas.className = `user__name`;
        userDatas.innerText = `Admin 123`;
        userData.appendChild(userDatas);

        userDatas = document.createElement(`div`);
        userDatas.className = `user__city`;
        userDatas.innerText = `Warszawa`;
        userData.appendChild(userDatas);

        userDatas = document.createElement(`div`);
        userDatas.className = `user__age`;
        userDatas.innerText = `18`;
        userData.appendChild(userDatas);

        userContainer.appendChild(userData);
        main.appendChild(userContainer);

        console.log(userContainer);
    }
}

const postForm = document.querySelector(`.form__post`);

postForm.addEventListener(`submit`, event => {
    event.preventDefault();

    const post = new Post(undefined);
    post.addPost();
});