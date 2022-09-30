import { throttle } from "../utils"

export default () => {
    const menu = document.getElementById('site-header')
    console.log(menu.classList)
    const revertOnScroll = () => {
        if (document.body.scrollTop > 0) {
            menu.classList.add('reverted')
        } else {
            menu.classList.remove('reverted')
        }
    }
    window.addEventListener("scroll", throttle(revertOnScroll, 15), true)
}