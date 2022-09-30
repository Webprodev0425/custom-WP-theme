const progressBar = () => {
  const scrollTop = document.body["scrollTop"]
  const body = document.querySelector("main")
  const scrollBottom = Math.max(body.scrollHeight, body.offsetHeight)
  const scrollPercent = (scrollTop / scrollBottom) * 100 + "%"
  document
    .getElementById("_progress")
    .style.setProperty("--scroll", scrollPercent)
}

export default progressBar
