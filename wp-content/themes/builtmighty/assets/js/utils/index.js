// shorthand debugging
export const log = logMessage => {
  console.log(logMessage)
}

// callback loop
export const loop = (times, callback) => {
  for (let i = 0; i < times; i++) {
    callback()
  }
}

// throttler for stuff like scroll events
export const throttle = (fn, wait) => {
  let time = Date.now()
  return function () {
    if (time + wait - Date.now() < 0) {
      fn()
      time = Date.now()
    }
  }
}

export const whenDOMFullyLoaded = func => {
  window.addEventListener("DMOContentLoaded", func())
}

export const addClasses = (el, classNames) => {
  classNames.map(className => {
    el.classList.add(className)
  })
}

export const addId = (el, id) => {
  el.id = id
}

export const removeClasses = (el, classNames) => {
  classNames.map(className => {
    el.classList.remove(className)
  })
}

export const applyStyles = (el, styles) => {
  Object.keys(styles).map(property => {
    el.style[property] = styles[property]
  })
}

export const createNode = type => {
  const node = document.createElement(type)
  return node
}

export const getNodeBySelector = el => {
  const node = document.querySelector(el)
  return node
}

export const copyNode = el => {
  const node = getNodeBySelector(el)
  const clone = node.cloneNode(true)
  return clone
}

export const prependNodeContent = (node, contentProps) => {
  node.insertAdjacentHTML(contentProps.position, contentProps.content)
}

export const prependNodeContents = (node, contents) => {
  contents.map(content =>
    prependNodeContent(node, {
      position: content.position,
      content: content.content,
    })
  )
}

export const checkForModule = module =>
  document.querySelector(module) ? true : false

export const appendNode = (el, node) => {
  const parent = document.querySelector(el)
  parent.appendChild(node)
}

export const deleteNode = node => {
  node.remove()
}

export const extractNodes = selectors => {
  const nodes = selectors.map(selector => copyNode(selector))
  return nodes
}

export const containNodes = (containerProps, nodes) => {
  const container = createNode(containerProps.type)

  if (containerProps.classes) {
    addClasses(container, containerProps.classes)
  }
  if (containerProps.id) {
    addId(container, containerProps.id)
  }

  nodes.map(node => container.appendChild(node))
  return container
}

export const addHtml = (elProps, htmlProps) => {
  const markup = createNode(htmlProps.container)
  const el = getNodeBySelector(elProps.el)
  addClasses(markup, htmlProps.classes)
  addClasses(el, elProps.classes)
  prependNodeContent(markup, {
    content: htmlProps.html,
    position: htmlProps.position,
  })
  el.insertBefore(markup, el.childNodes[0])
}

export const toggleActiveClass = el => {
  !el.classList.contains("active")
    ? addClasses(el, ["active"])
    : removeClasses(el, ["active"])
}

export const toggleHamburgerActiveClass = el => {
  !el.classList.contains("crossed")
    ? addClasses(el, ["crossed"])
    : removeClasses(el, ["crossed"])
}

export const toggleMenu = () => {
  const menu = getNodeBySelector("#bm-mobile-nav")
  const hamburger = getNodeBySelector(".hamburger")
  toggleActiveClass(menu)
  toggleHamburgerActiveClass(hamburger)
}

export const scrollToEl = el => {
  el.scrollIntoView(true, { behavior: "smooth" })
}

export const preloadImage = url => {
    const img = new Image();
    img.src = url;
}