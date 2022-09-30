const { getNodeBySelector } = require(".")

const getPostType = () => {
  const classes = getNodeBySelector("body").classList
  return classes[1]
}

export default getPostType
