import { preloadImage } from "../utils";

export default () => {
    const hoverSquares = document.querySelectorAll('.hover-square');
    [...hoverSquares].forEach(square => {
        const imgSrc = square.dataset.imgsrc;
        imgSrc && preloadImage(imgSrc);
        square.addEventListener('mouseover', () => {
            if(!imgSrc) return;
            square.closest('.team-block__member').querySelector('.team-block__member-image').src = imgSrc;
        });
    });
}