const animatePosts = (posts) => {
    posts.forEach(post => {
        if(post.getBoundingClientRect().top <= window.screen.height-300) {
            post.classList.add(`--load`);
        } else post.classList.remove(`--load`);
    });
}

document.addEventListener(`DOMContentLoaded`, () => {
    let posts = document.querySelectorAll(`.post__container`);

    animatePosts(posts);

    window.addEventListener(`scroll`, () => animatePosts(posts));
})