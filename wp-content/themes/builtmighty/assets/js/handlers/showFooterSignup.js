const { getNodeBySelector, addClasses, removeClasses } = require("../utils")

const showFooterSignup = () => {
  const footerSignup = getNodeBySelector("#footer-signup")
  const button = getNodeBySelector("#open-signup")
  const toggleForm = () => {
    if (!footerSignup.classList.contains("open")) {
      addClasses(footerSignup, ["open"])
      addClasses(button, ["open"])
    } else {
      removeClasses(footerSignup, ["open"])
      removeClasses(button, ["open"])
    }
  }
  button.addEventListener("click", toggleForm)
}

export default showFooterSignup
