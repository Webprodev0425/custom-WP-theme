import Cookies from "js-cookie"

// cookies
export const getCookie = type => Cookies.get(type)
export const setCookie = type => {
  Cookies.set(type, true, { expires: 365 })
}
