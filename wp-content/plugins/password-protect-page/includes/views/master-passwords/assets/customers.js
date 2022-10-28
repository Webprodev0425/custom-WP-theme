jQuery(document).ready(function () {
   let timer = setInterval(() => {
        if (jQuery(".ppwp-group .ant-table-scroll table tbody.ant-table-tbody").length > 0) {
            jQuery("td.ant-table-column-has-actions.ant-table-column-has-sorters div").each(function () {
                var user_name = jQuery(this).text()
                if (user_name.indexOf("Role") != -1) {
                    let user = user_name.replace("Role", "User")
                    jQuery(this).text(user)
                    console.log(user_name)
                }
            })
        }
    }, 500)
    jQuery("button#ppw-add-master-password").click(function () {
        console.log("Add new password")
        setInterval(() => {
            jQuery(".ant-select-dropdown").each(function () {
                var clsname = jQuery(this).attr("class");
                if(clsname.indexOf("ant-select-dropdown-hidden") != -1){
                    console.log("Hidden")
                }
                else {
                    jQuery(this).find(".ant-select-dropdown-menu-item").each(function () {
                        var role = jQuery(this).text();
                        if(role == "administrator" || role == "editor" || role == "author" || role == "contributor" || role == "subscriber" || role == "customer" || role == "shop_manager" || role == "wpseo_manager" || role == "wpseo_editor"){
                            jQuery(this).hide()
                        }
                    })
                }
            })
        },200)
    })
})