import { addClasses, removeClasses } from "../utils"

const showFooterMenu = menus => {
  menus = [].slice.call(menus)
  const decideToSlide = menu => {
    !menu.classList.contains("active")
      ? addClasses(menu, ["active"])
      : removeClasses(menu, ["active"])
  }
  menus.forEach(menu => {
    const shoudISlide = () => {
      decideToSlide(menu)
    }
    menu.addEventListener("click", shoudISlide)
  })
}

export default showFooterMenu
