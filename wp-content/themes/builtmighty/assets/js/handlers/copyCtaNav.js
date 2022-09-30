import { copyNode, addClasses, checkForModule } from "../utils"

const copyCtaNav = () => {
  if (checkForModule(".secondary-cta")) {
    return
  }
  const ctaNav = "#cta-menu"
  const ctaClone = copyNode(ctaNav)
  addClasses(ctaClone, ["secondary-cta"])
  return ctaClone
}

export default copyCtaNav
