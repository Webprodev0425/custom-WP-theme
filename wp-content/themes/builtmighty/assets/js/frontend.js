import "@babel/polyfill"
import getPostType from "./utils/meta"
import { runOnWindowWidth, runOverWindowWidth } from "./utils/window"
import { createNode, addClasses, removeClasses } from "./utils"
import { checkForHash } from "./utils/window-location"

const init = () => {
  const colorMenu = async () => {
    const {default: menuBgColor} = await import("./handlers/menuBgColor")
    menuBgColor()
  }
  colorMenu()
  
  const stopAboveHash = async () => {
    if (!checkForHash()) {
      return
    }
    const { scrollPastHash } = await import("./utils/window-location")
    const hash = checkForHash()
    scrollPastHash(hash)
  }
  stopAboveHash()

  const revertMenu = async () => {
    const { default: revertMenuColors } = await import("./handlers/revertMenuColors")
    revertMenuColors()
  }
  revertMenu()

  // rotating text for our shortcode, see: /inc/shortcodes.php
  const rotateText = async () => {
    const textToRotate = document.querySelector(".rotate-text") ? document.querySelector(".rotate-text").dataset.rotate : null
    const { default: rotatingText } = await import("./handlers/rotatingText")
    textToRotate && rotatingText(textToRotate)
  }
  rotateText()

  // for the team page
  const bradyBunchEffect = async () => {
    const { default: bradyBunch } = await import("./handlers/bradyBunch")
    bradyBunch()
  }
  bradyBunchEffect()

  // show progress bar at top of screen, on posts
  const showProgressBar = async () => {
    if (getPostType() !== "single") {
      return
    }
    const { default: progressBar } = await import("./handlers/progressBar")
    document.addEventListener("scroll", progressBar, true)
  }
  showProgressBar()

  const addStatisticCount = async () => {
    const { default: statisticCount } = await import(
      "./handlers/statisticCount"
    )
    statisticCount()
  }
  
  addStatisticCount()
}

// initialize site
init()
