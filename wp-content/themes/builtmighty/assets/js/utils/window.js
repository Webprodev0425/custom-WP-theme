import { throttle } from "."

export const isWindowGreaterOrEqual = compareWidth => {
  const width = window.innerWidth
  return parseInt(compareWidth, 10) >= width
}

export const isWindowLessOrEqual = compareWidth => {
  const width = window.innerWidth
  return parseInt(compareWidth, 10) <= width
}

export const runOnWindowWidth = (width, callback) => {
  const maybeRunCallback = () => {
    if (isWindowGreaterOrEqual(width)) {
      callback()
    }
  }
  maybeRunCallback()
  window.addEventListener("load", maybeRunCallback)
  window.addEventListener("resize", maybeRunCallback)
}

export const runOverWindowWidth = (width, callback) => {
  const maybeRunCallback = () => {
    if (isWindowLessOrEqual(width)) {
      callback()
    }
  }
  maybeRunCallback()
  window.addEventListener("load", maybeRunCallback)
  window.addEventListener("resize", maybeRunCallback)
}

export const runBetweenWidths = ({ min, max } = {}, callback) => {
  const maybeRunCallback = () => {
    if (isWindowGreaterOrEqual(max) && isWindowLessOrEqual(min)) {
      callback()
    }
  }
  maybeRunCallback()
  window.addEventListener("resize", maybeRunCallback)
}

export const runIfPastElandBeforeEl2 = (el, el2, callback1, callback2) => {
  const maybeRunCallbacks = () => {
    const elBoundsTop = el.getBoundingClientRect().top + el.offsetHeight
    const el2BoundsTop = el2.getBoundingClientRect().top
    if (elBoundsTop < 0 && el2BoundsTop > 0) {
      callback1()
    } else {
      callback2()
    }
  }
  maybeRunCallbacks()
  window.addEventListener("scroll", throttle(maybeRunCallbacks, 25), true)
}
