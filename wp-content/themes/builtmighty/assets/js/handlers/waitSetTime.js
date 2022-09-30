import setTimeLeftInSession from "../session-methods/setTimeLeftInSession"

const waitSetTime = (time, action) => {
  let seconds = time
  const countdown = setInterval(function () {
    seconds--
    setTimeLeftInSession(seconds)
    if (seconds <= 0) {
      clearInterval(countdown)
      action()
    }
  }, 1000)
}
export default waitSetTime
