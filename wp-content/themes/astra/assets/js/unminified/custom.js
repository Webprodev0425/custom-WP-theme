$(document).ready(function(){
    //timer
    let timer = setInterval(() => {
        // select an element in an iframe
        if($("#hs-form-iframe-0").contents().find("input.hs-button.primary.large").length > 0){
            clearInterval(timer)
            $("iframe#hs-form-iframe-0").contents().find("input.hs-button.primary.large").click(function(){
                var pass = (Math.random() + 5).toString(36).substring(3);
                var email = $("iframe#hs-form-iframe-0").contents().find(".hs_email input").val()
                var f_name = $("iframe#hs-form-iframe-0").contents().find(".hs_firstname input").val()
                var data = {
                    'action': 'my_ajax_request',
                    'post_type': 'POST',
                    'name': f_name,
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