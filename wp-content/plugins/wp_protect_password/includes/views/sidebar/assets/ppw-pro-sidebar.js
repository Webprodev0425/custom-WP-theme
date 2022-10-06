(function ($) {
	'use strict';

	$(function () {
		$('#ppwp_subscribe_form').submit(function (evt) {
			evt.preventDefault();
			const email = $("#ppwp_email_subscribe").val().trim();
			const emailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if (!emailPattern.test(email)) {
				$(".ppwp_subscribe_error").show("slow");
				$("#ppwp_email_subscribe").focus();
				$("#ppwp_subscribe_button").val("Get Lucky");
			} else {
				$("#ppwp_subscribe_button").val("Saving...");
				handlesubscribe($, {
					ppwp_email: email,
				}, function (result, error) {
					if (result) {
						$("#ppwp_subscribe_form").hide();
						$("#ppwp_subscribe_form_success").show();
					} else if (error) {
						if (400 === error.status) {
							$(".ppwp_subscribe_error").text(error.responseJSON.message);
							$(".ppwp_subscribe_error").show("slow");
						} else {
							$(".ppwp_subscribe_error").text("Oops! Something went wrong. Please reload the page and try again.");
							$(".ppwp_subscribe_error").show("slow");
						}
					}
					$("#ppwp_subscribe_button").val("Get Lucky");
				});
			}
		});

		function handlesubscribe($, settings, cb) {
			var _data = {
				action: 'ppwp_pro_subscribe_request',
				settings: settings,
				security_check: $("#ppwp_subscribe_form_nonce").val(),
			};
			ajaxRequest($, _data, cb);
		}

		function ajaxRequest($, _data, cb) {
			$.ajax({
				url: ppw_pro_sidebar_data.ajax_url,
				type: 'POST',
				data: _data,
				timeout: 5000,
				success: function (data) {
					cb(data, null);
				},
				error: function (error) {
					cb(null, error);
				},
			});
		}
	});
})(jQuery);
