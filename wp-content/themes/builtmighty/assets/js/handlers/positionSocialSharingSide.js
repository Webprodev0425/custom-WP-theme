import { getNodeBySelector } from "../utils/index"
import { runIfPastElandBeforeEl2 } from "../utils/window"
const positionSocialSharingSide = () => {
  const getDistanceFromSide = () => {
    const postContainer = getNodeBySelector(".post-container")
    let distanceFromSide = `${
      postContainer.getBoundingClientRect().left - 15
    }px`
    return distanceFromSide
  }
  const fixSocialShareToSide = () => {
    const socialShareSide = getNodeBySelector(".social_share_side")
    const runFunction = () => {
      if (window.innerWidth > 1170) {
        socialShareSide.style.left = getDistanceFromSide()
      } else {
        socialShareSide.style.left = 0
      }
    }
    runFunction()
    window.addEventListener("resize", runFunction)
  }
  const showOrHide = () => {
    fixSocialShareToSide()
    const socialShareTop = getNodeBySelector("#author")
    console.log(socialShareTop)
    const promo = getNodeBySelector(".bio")
    console.log(promo)
    const socialShareSide = getNodeBySelector(".social_share_side")
    console.log(socialShareSide)
    const showSocialShareSide = () => (socialShareSide.style.display = "flex")
    const hideSocialShareSide = () => (socialShareSide.style.display = "none")
    runIfPastElandBeforeEl2(
      socialShareTop,
      promo,
      showSocialShareSide,
      hideSocialShareSide
    )
  }
  showOrHide()
}

export default positionSocialSharingSide
