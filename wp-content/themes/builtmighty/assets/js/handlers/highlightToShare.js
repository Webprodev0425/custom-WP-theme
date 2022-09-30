import highlightShare from "highlight-share"
import * as twitterSharer from "highlight-share/dist/sharers/twitter"
import * as facebookSharer from "highlight-share/dist/sharers/facebook"
import * as emailSharer from "highlight-share/dist/sharers/email"

const highlightToShare = () => {
  const addbmHandleToText = text => `${text} @ConstantContact`
  const selectionShare = highlightShare({
    selector: ".content-side",
    sharers: [twitterSharer, facebookSharer, emailSharer],
    transformer: addbmHandleToText,
  })

  if (!window.matchMedia || !window.matchMedia("(pointer: coarse)").matches) {
    selectionShare.init()
  }
}

export default highlightToShare
