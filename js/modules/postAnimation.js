const loadPosts = (posts) => {
    posts.forEach(post => {
        if(post.getBoundingClientRect().top <= window.screen.height-300) {
            post.classList.add(`--load`);
        }
    });
}

document.addEventListener(`DOMContentLoaded`, () => {
    let posts = document.querySelectorAll(`.post__container`);

    loadPosts(posts);

    window.addEventListener(`scroll`, () => loadPosts(posts));
})