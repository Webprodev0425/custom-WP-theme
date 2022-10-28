$(document).ready(function(){
    //timer
    let timer = setInterval(() => {
        // select an element in an iframe
        if($(".hs-form-iframe").contents().find("input.hs-button.primary.large").length > 0){
            clearInterval(timer)
            $("iframe.hs-form-iframe").contents().find("input.hs-button.primary.large").click(function(){
                let pass = (Math.random() + 5).toString(36).substring(3);
                let email = $("iframe.hs-form-iframe").contents().find(".hs_email input").val()
                let f_name = $("iframe.hs-form-iframe").contents().find(".hs_firstname input").val()
                let l_name = $("iframe.hs-form-iframe").contents().find(".hs_lastname input").val()
                let name = f_name.charAt(0).toUpperCase() + f_name.slice(1) + " " + l_name.charAt(0).toUpperCase() + l_name.slice(1)
                let data = {
                    'action': 'my_ajax_request',
                    'post_type': 'POST',
                    'name': name,
                    'email': email,
                    'pass': pass
                  };
                  //ajax post request send
                $.post("/wordpress/wp-admin/admin-ajax.php", data, function(response) {
                    console.log( response );
                    }, 'json');
                })
            }
    }, 200);
})