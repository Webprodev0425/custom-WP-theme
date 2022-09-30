import { runOverWindowWidth, runOnWindowWidth } from "../utils/window"
import { addClasses, removeClasses } from "../utils"

const showSubMenu = (triggerListeners, menuListeners) => {
  const triggers = [].slice.call(
    document.querySelectorAll(".menu-item-has-children")
  )

  const decideToSlide = menu => {
    !menu.classList.contains("active")
      ? addClasses(menu, ["active"])
      : removeClasses(menu, ["active"])
  }
  const addEvents = events => {
    triggers.forEach(trigger => {
      const amIActive = () => {
        decideToSlide(trigger)
      }
      events.map(event => trigger.addEventListener(event, amIActive))
    })
  }
  const desktopMenu = () => addEvents(["mouseenter", "mouseleave"])
  const mobileMenu = () => addEvents(["click"])

  runOverWindowWidth("1400", desktopMenu)
  runOnWindowWidth("1400", mobileMenu)
}

export default showSubMenu
