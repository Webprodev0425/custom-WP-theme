const { default: getTimeLeftInSession } = require("./getTimeLeftInSession")
const { default: setTimeLeftInSession } = require("./setTimeLeftInSession")

const isNewUser = () => {
  if (!getTimeLeftInSession() || isNaN(getTimeLeftInSession())) {
    setTimeLeftInSession(121)
    // this one's for testing purposes
    // setTimeLeftInSession(1)
  }
}

export default isNewUser
