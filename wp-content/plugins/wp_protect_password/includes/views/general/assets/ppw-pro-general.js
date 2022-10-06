(function ($) {
    'use strict';

    $(function () {

        handleChangeElement();

        $('#wp_protect_password_general_form').submit(function (evt) {
            evt.preventDefault();
            if (typeof ($("#ppwp_set_new_password_for_pages_posts").prop('checked')) !== "undefined") {
                if ($("#ppwp_set_new_password_for_pages_posts").prop('checked')) {
                    var password = $('#ppwp-password-for-pages-posts').val();
                } else {
                    var password = $('#ppwp-password-hidden').val();
                }
            } else {
                var password = $('#ppwp-password-for-pages-posts').val();
            }
	        const data = getDataFromClient( password );
            _updateSettings(data, function (result, error) {
                var pluginName = 'Password Protect WordPress Pro';
                if (result) {
                    toastr.success('Your settings have been updated successfully!', pluginName);
                    location.reload(true);
                }

                if (error) {
                    if (400 === error.status) {
                        toastr.error(error.responseJSON.message, pluginName);
                    } else {
                        toastr.error('Fail to update the setting.', pluginName);
                    }
                    console.log('Data error', error);
                    $("#submit").prop("disabled", false);
                }
            });
        });

    });

	function getDataFromClient( password ) {
		let results = {
			wpp_password_cookie_expired: $("#wpp_password_cookie_times").val() + ' ' + $("#wpp_password_cookie_units").val(),
			wpp_whitelist_roles: $("#wpp_whitelist_roles").val(),
			wpp_roles_select: $("#wpp_roles_select").val(),
			wpp_apply_password_for_pages_posts: $("#ppwp_apply_password_for_pages_posts").prop('checked'),
			wpp_pages_posts_select: $("#ppwp-pages-posts-select").val(),
			wpp_password_for_pages_posts: password,
			wpp_auto_protect_all_child_pages: $("#ppwp_auto_protect_all_child_pages").prop('checked'),
			wpp_remove_data: $("#wpp_remove_data").prop('checked'),
			wpp_error_message: $('#wpp_error_message').val(),
			wpp_form_message: $('#wpp_form_message').val(),
			wpp_whitelist_column_protections: $('#ppwp_whitelist_column_protections_select2').val(),
			ppwp_remove_search_engine: $("#ppwp_remove_search_engine").prop('checked'),
		};
		// Handle get data for Hide Protect Content
		const postTypes = ppw_pro_setting_data.post_type;
		const hidePostData = postTypes.map(function(postType) {
			return {
				["ppw_hide_protected_" + postType]: $('#ppw_hide_protected_' + postType).prop("checked"),
				["ppw_hide_selected_" + postType]: $('#ppw_hide_selected_' + postType).val(),
			}
		});
		hidePostData.forEach(function(dataPost) {
			let keys = Object.keys(dataPost);
			keys.forEach(function(key) {
				results[key] = dataPost[key];
			});
		});

		return results;
	}

    function scan_post_type() {
        $("#ppwp_scan_post_type").val('Migrating...');
        $("#ppwp_scan_post_type").prop("disabled", true);
        $('.ppwp_loading_button_for_column_permission').show();
        var settings = {
            "url": ppw_pro_setting_data.home_url + "wp-json/wppp/v1/migrate-default-password",
            "method": "POST",
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded",
                'X-WP-Nonce': ppw_pro_setting_data.nonce
            },
        };

        $.ajax(settings).done(function (response) {
            if (response) {
                toastr.success('Your passwords have been migrated successfully.', 'Password Protect WordPress Pro');
            } else {
                toastr.error('Failed to migrate your passwords. Please contact the plugin owner for more details.', 'Password Protect WordPress Pro');
            }
            $('.ppwp_loading_button_for_column_permission').hide();
            $("#ppwp_scan_post_type").val('Migrate Passwords');
            $("#ppwp_scan_post_type").prop("disabled", false);
        });
    }

    function handleChangeElement() {
        $('.ppwp_select2').select2({
            width: '100%',
        });
        $("#wpp_whitelist_roles").change(function () {
            if ($(this).val() === 'custom_roles') {
                $('#wpp_roles_access').attr('required', true);
                $('#wpp_roles_access').show();
                $('#wpp_roles_select').prop('required', true);
            } else {
                $('#wpp_roles_access').attr('required', false);
                $('#wpp_roles_access').hide();
                $('#wpp_roles_select').prop('required', false);
            }
        });
        $("#wpp_whitelist_roles").trigger("change");

        $("#ppwp-password-for-pages-posts").change(function () {
            if ($(this).val().indexOf(" ") !== -1) {
                toastr.error('Please remove spaces in password!', 'Password Protect WordPress Pro');
                $("#submit").prop("disabled", true);
            } else {
                $("#submit").prop("disabled", false);
            }
        });

        $("#ppwp_apply_password_for_pages_posts").change(function () {
            if ($(this).prop('checked')) {
                $(".ppwp-pages-posts-set-password").show();
                $('#ppwp-pages-posts-select').attr('required', true);
                $('#ppwp-password-for-pages-posts').attr('required', true);
                $("#ppwp-password-for-pages-posts").trigger("change");
                $("#ppwp_set_new_password_for_pages_posts").trigger('change');
            } else {
                $(".ppwp-pages-posts-set-password").hide();
                $('#ppwp-pages-posts-select').attr('required', false);
                $('#ppwp-password-for-pages-posts').attr('required', false);
                $("#submit").prop("disabled", false);
            }
        });
        $("#ppwp_apply_password_for_pages_posts").trigger('change');

        $("#ppwp_set_new_password_for_pages_posts").change(function () {
            if ($(this).prop('checked')) {
                $('#ppwp-password-for-pages-posts').attr('required', true);
                $("#ppwp-new-password").show();
                $("#ppwp-password-for-pages-posts").trigger("change");
            } else {
                $('#ppwp-password-for-pages-posts').attr('required', false);
                $("#ppwp-new-password").hide();
                $("#submit").prop("disabled", false);
            }
        });
        $("#ppwp_set_new_password_for_pages_posts").trigger('change');

        // Handle change Post Type Protection
        $('#ppwp_scan_post_type').click(function (evt) {
            // scan_post_type();
        });

        /* Handle change cookie expired */
        $('#wpp_password_cookie_units').change(function () {
            console.log($(this).val());
            setMinMaxForCookieExpired($(this).val());
        });

	    /* Handle change "Hide Protected Content" */
	    $("#ppw_select_custom_post_type_edit").change(function () {
		    $('.ppw_hide_protect_content').hide();
		    const postTypeEdit = $(this).val();
		    if (postTypeEdit === 'page_post') {
			    const pageAndPost = ['post', 'page'];
			    pageAndPost.forEach(function(postType) {
				    handleChangeEditPostType(postType);
			    })
		    } else {
			    handleChangeEditPostType(postTypeEdit);
		    }

		    const postTypes = ppw_pro_setting_data.post_type;
		    postTypes.forEach(function(postType) {
		    	const positionSelected = $('#ppw_hide_selected_' + postType).val();
		    	if ( null === positionSelected ) {
				    $("#ppw_hide_protected_" + postType).prop("checked", false);
				    $("#ppw_hide_protected_" + postType).trigger('change');
			    }
		    });

	    });
	    $("#ppw_select_custom_post_type_edit").trigger('change');
    }

	function handleChangeEditPostType(postType) {
		$('.ppw_wrap_' + postType).show();
		$('#ppw_hide_protected_' + postType).change(function () {
			if (this.checked) {
				checkLogicShowElement('#ppw_wrap_hide_selected_' + postType, '#ppw_hide_selected_' + postType);
			} else {
				checkLogicHideElement('#ppw_wrap_hide_selected_' + postType, '#ppw_hide_selected_' + postType);
			}
		});
	}

	function checkLogicShowElement(idElement1, idElement2) {
		$(idElement1).show();
		$(idElement2).prop('required', true);
	}

	function checkLogicHideElement(idElement1, idElement2) {
		$(idElement1).hide();
		$(idElement2).prop('required', false);
	}




    function _updateSettings(settings, cb) {
        var _data = {
            action: 'ppw_pro_handle_settings',
            settings: settings,
            security_check: $("#ppw_general_form_nonce").val(),
        }
        ajaxRequest(_data, cb);
    }

    function ajaxRequest(_data, cb) {
        $("#submit").prop("disabled", true);
        $.ajax({
            url: ppw_pro_setting_data.ajax_url,
            type: 'POST',
            data: _data,
            success: function (data) {
                cb(data, null);
            },
            error: function (error) {
                cb(null, error);
            },
            timeout: 5000
        });
    }

    function setMinMaxForCookieExpired(unitsTimes) {
        var maxCookie = 365;
        switch (unitsTimes) {
            case 'days':
                $("#wpp_password_cookie_times").attr({
                    "max": maxCookie,
                });
                break;
            case 'hours':
                $("#wpp_password_cookie_times").attr({
                    "max": maxCookie * 24,
                });
                break;
            case 'minutes':
                $("#wpp_password_cookie_times").attr({
                    "max": maxCookie * 24 * 60,
                });
                break;
        }
    }

})(jQuery);
