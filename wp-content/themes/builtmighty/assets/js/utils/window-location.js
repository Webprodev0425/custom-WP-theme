import { getNodeBySelector } from "."

export const checkForHash = () => window.location.hash

export const scrollPastHash = hash => {
  hash = getNodeBySelector(hash)
  let winPos = hash.offsetTop + 100
  window.scrollTo(0, winPos)
}
