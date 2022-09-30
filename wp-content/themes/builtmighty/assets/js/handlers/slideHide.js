import { applyStyles, addClasses, removeClasses } from "../utils"

// click to show and hide a container, with a slide effect
const slideHide = (button, container, actions) => {
  if (!button) {
    return
  }
  // apply initial required styles
  const containerStyles = {
    overflowY: "hidden",
    transition: "0.5s all ease",
    maxHeight: "0",
  }
  const buttonStyles = {
    transition: "0.3s all ease",
    cursor: "pointer",
  }

  applyStyles(container, containerStyles)
  applyStyles(button, buttonStyles)

  // create the animations
  const openContainer = () => {
    applyStyles(container, { maxHeight: "9999px" })
    applyStyles(button, { transform: "rotate(180deg)" })
    addClasses(container, ["active"])
    addClasses(button, ["toggle-close"])
  }

  const closeContainer = () => {
    applyStyles(container, { maxHeight: "0" })
    applyStyles(button, { transform: "rotate(0deg)" })
    removeClasses(container, ["active"])
    removeClasses(button, ["toggle-close"])
  }

  const decideToSlide = () => {
    !container.classList.contains("active") ? openContainer() : closeContainer()
  }

  // tell the button to animate
  actions.map(action => button.addEventListener(action, decideToSlide))
}

export default slideHide
