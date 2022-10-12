jQuery(document).ready(function () {
    jQuery("button#ppw-add-master-password").click(function () {
        console.log("Add new password")
        var timer = setInterval(()=> {
            if(jQuery(".ant-select-dropdown-menu").length > 0){
                clearInterval(timer)
                jQuery(".ant-select-dropdown-menu li").each(function () {
                    jQuery(this).click(function () {
                        console.log("Clicked")
                    })
                    console.log("List")
                })
            }
        }, 200)
    })
})