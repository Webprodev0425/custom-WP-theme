$(document).ready(function(){
    console.log("Custom JS")
    //timer
    var timer = setInterval(() => {
        // select an element in an iframe
        if($("#hs-form-iframe-0").contents().find("input.hs-button.primary.large").length > 0){
            clearInterval(timer)
            console.log("Form is loaded")
            $("iframe#hs-form-iframe-0").contents().find("input.hs-button.primary.large").click(function(e){
                var email = $("iframe#hs-form-iframe-0").contents().find(".hs_email input").val()
                var fname = $("iframe#hs-form-iframe-0").contents().find(".hs_firstname input").val()
                var data = {
                    'action': 'my_ajax_request',
                    'post_type': 'POST',
                    'name': fname,
                    'email': email
                  };
                  //ajax post request send
                $.post("/wordpress/wp-admin/admin-ajax.php", data, function(response) {
                    console.log( response );
                    }, 'json');
                })
        }
    }, 200);
})