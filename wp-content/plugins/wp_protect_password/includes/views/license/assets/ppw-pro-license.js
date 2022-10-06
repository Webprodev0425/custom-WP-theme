(function ($) {
    'use strict';

    $(function () {
        $('#wp_protect_password_license_form').submit(function (evt) {
            evt.preventDefault();
            var license = $('#wp-protect-password-gold_license_key').val();
            var pluginName = 'Password Protect WordPress Pro';
            _checkLicense(license, function (result, error) {
                $('#submit').val('Save Changes');
                $("#submit").prop("disabled", false);
                if (result) {
                    toastr.success(result.message, pluginName);
                    location.reload(true);
                }

                if (error) {
                    if (400 === error.status) {
                        toastr.error(error.responseJSON.message, pluginName);
                    } else {
                        toastr.error('License invalid!', pluginName);
                    }
                    console.log('Data error', error);
                }
            });
        });
    });

    function _checkLicense(license, cb) {
        var _data = {
            action: 'ppw_pro_check_license',
            license: license,
            security_check: $("#ppw_license_nonce").val(),
        }
        $('#submit').val('Submitting');
        $("#submit").prop("disabled", true);
        $.ajax({
            url: ppw_license_data.ajax_url,
            type: 'POST',
            data: _data,
            success: function (res) {
                cb(res, null);
            },
            error: function (error) {
                $("#submit").prop("disabled", false);
                cb(null, error);
            }

        })
    }
})(jQuery);
