const loadPosts = (posts) => {
    posts.forEach(post => {
        if(post.getBoundingClientRect().top <= window.screen.height+200) {
            post.classList.add(`--load`);
        }
    });
}

document.addEventListener(`DOMContentLoaded`, () => {
    let posts = document.querySelectorAll(`.post__container`);

    posts.style.display='none';

    loadPosts(posts);

    window.addEventListener(`scroll`, () => loadPosts(posts));
})