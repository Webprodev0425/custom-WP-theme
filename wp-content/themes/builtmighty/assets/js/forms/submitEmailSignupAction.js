const submitEmailSignupAction = props => {
  const jmmlForm = document.querySelector(props.formSelector)
  const jmmlFormEmail = document.querySelector(props.emailSelector)
  const TIMEOUT_THANKYOU = 2000
  const reportJMMLActivityToSiteCat = trackedAction => {
    try {
      s.eVar26 = trackedAction
      s.eVar48 = "body>" + s.eVar26
      s.eVar49 = window.location.pathname + ">" + s.eVar48
      s.events = "event17"
      s.tl()
    } catch (error) {
      window.console.error("SiteCatalyst not loaded")
    }
  }
  const createIframe = event => { 
    var salesforceEndpoint = jmmlForm.action
    if (jmmlFormEmail.validity.valid === true) {
      event.preventDefault()
      const showThankYou = () => {
        $(props.formSelector).html(
          '<p style="margin-bottom: 150px;">Got it! Look out for our bi-weekly marketing tips in your inbox</p>'
        )
      }
      $("<iframe>", {
        src: `${salesforceEndpoint}?${$(jmmlForm).serialize()}`,
        height: 0,
        width: 0,
        style: "display: none;",
      }).appendTo(document.body)

      reportJMMLActivityToSiteCat("join - success")
      window.setTimeout(showThankYou, TIMEOUT_THANKYOU)
    }
  }
  jmmlForm.addEventListener("submit", createIframe)
}

export default submitEmailSignupAction
