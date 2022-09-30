export default () => {
    const menu = document.querySelector('#site-header');
    const hero = document.querySelector('.hero');
    const color = hero && hero.classList.contains('gray');
    color && menu.classList.add('gray');
}