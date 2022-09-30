import { getNodeBySelector, scrollToEl } from "./index.js"

export const ajaxQuery = props => {
  return {
    action: props.action,
    page: props.page,
    query: {
      tax: props.query.tax,
      term: props.query.term,
    },
  }
}

export const ajaxPost = props => {
  const $ = jQuery
  $.post(props.ajaxUrl, props.query).done(posts => {
    if (props.callback) {
      props.callback()
    }
    props.container.html(posts)
    props.postsArea.css("opacity", 1)
    if (!props.initialRequest) {
      scrollToEl(document.getElementById("featured-articles-heading"))
    }
  })
}

export const getAjaxUrl = () =>
  `${window.location.protocol}//${window.location.hostname}/wp-admin/admin-ajax.php`

export const bmXmlRequest = props => {
  const feed = getNodeBySelector(".featured-articles")

  if (!feed) {
    return
  }

  const data = props.query
  console.log(data)
  // data.append("action", "bm_featured_ajax")
  // data.append("nonce", wp_pageviews_ajax.nonce)
  // data.append("is_user_logged_in", wp_pageviews_ajax.is_user_logged_in)
  // data.append("is_single", wp_pageviews_ajax.is_single)
  // data.append("postid", $el.dataset.postid)
  fetch(getAjaxUrl(), {
    method: "POST",
    credentials: "same-origin",
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(response => console.log(response))
    .catch(err => console.log(`error : ${err}`))
}
