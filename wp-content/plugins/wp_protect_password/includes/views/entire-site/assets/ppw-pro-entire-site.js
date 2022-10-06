(function ($) {
	'use strict';
	$(function () {
		const pluginName = 'Password Protect WordPress Pro';
		const idSwitchButton = '#ppwp_apply_password_for_entire_site';
		const idTextarea = '#ppw_password_entire_site';
		const idExcludePage = '#ppwp_page_exclude';
		const idSwitchExcludePage = '#ppwp_switch_exclude_page';
		const idSubmitButton = '#submit';
		const idForm = '#ppw_entire_site_form';
		const idRedirection = '#ppw_redirection';
		const idWrapRedirection = '#ppwp_wrap_redirection';
		const classTooltip = '.ppw-tooltip';
		handleChangeElement(idSwitchButton, idTextarea, idExcludePage, idSwitchExcludePage, idSubmitButton, idRedirection, idWrapRedirection);
		handleTooltip(classTooltip);
		$(idForm).submit(function (evt) {
			evt.preventDefault();
			if (!$(idSwitchButton).prop('checked')) {
				$(idTextarea).val('');
				$(idExcludePage).val(null).trigger('change');
				$(idSwitchExcludePage).attr('checked', false);
				$(idSwitchExcludePage).trigger('change');
			} else {
				if (!$(idSwitchExcludePage).prop('checked')) {
					$(idExcludePage).val(null).trigger('change');
				}
			}

			let passwords = $(idTextarea).val();

			passwords = passwords ? passwords
				.split('\n')
				.map(pass => pass.trim())
				.filter(pass => pass !== ""): [];
			const tmp = getPasswordObjAndCheckURLValid(passwords);
			const passwordObj = tmp.passwordObj;
			if (!tmp.isValidUrl) {
				$('.ppw_error_redirection').show();
				return;
			}

			_updatePassword({
				ppwp_apply_password_for_entire_site: $(idSwitchButton).prop('checked'),
				ppw_password_entire_site: passwordObj,
				ppwp_switch_exclude_page: $(idSwitchExcludePage).prop('checked'),
				ppwp_page_exclude: $(idExcludePage).val(),
				ppw_redirection: $(idRedirection).prop('checked'),
			}, function (result, error) {
				if (result) {
					const idNotice = '#ppw_notice_entire_site';
					$(idTextarea).val(passwords.join('\n'));
					toastr.success('Your settings have been updated successfully!', pluginName);
					$(idNotice).hide();
					location.reload(true);
				}

				if (error) {
					if (400 === error.status) {
						toastr.error(error.responseJSON.message, pluginName);
					} else {
						toastr.error('Fail to update the setting.', pluginName);
					}
					console.log('Data error', error);
				}

				$(idSubmitButton).prop("disabled", false);
			}, idSubmitButton);
		});

	});

	/**
	 * Get password obj and check whether the URLs are valid.
	 *
	 * @param passwords
	 * @returns {{passwordObj: Array, isValidUrl: boolean}}
	 */
	function getPasswordObjAndCheckURLValid( passwords ) {
		let passwordObj = [];
		let isValidUrl = true;
		const pwdUrlPrefix = 'ppw-url-pwd_';
		passwords.forEach(function (pass) {
			const e_pass = CSS.escape(btoa(pass));
			const redirect_url = $('#' + pwdUrlPrefix + e_pass).val() || '';
			if (redirect_url && !validateCustomLink(redirect_url)) {
				isValidUrl = false;
				return;
			}
			passwordObj.push({
				pass,
				redirect_url,
			});
		});

		return {
			passwordObj,
			isValidUrl
		}
	}

	/**
	 * Check the link is valid format.
	 *
	 * @param link
	 * @returns {boolean}
	 */
	function validateCustomLink(link) {
		const validate = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.([a-zA-Z]{1,63}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+){1,256})*$/;
		return validate.test(link);
	}

	function showWarningMessage(message, idSubmitButton, idWrongMessage) {
		$(idWrongMessage).show();
		$(idSubmitButton).prop("disabled", true);
		$(idWrongMessage).text(message);
	}

	function handleChangeElement(idSwitchButton, idTextarea, idExcludePage, idSwitchExcludePage, idSubmitButton, idRedirection, idWrapRedirection) {
		const idWrongMessage = '#ppw_wrong_password';
		const classSelect2 = '.ppwp_select2';
		$(classSelect2).select2({
			width: '100%',
		});

		$(idRedirection).change(function () {
			if ($(this).prop('checked')) {
				$(idWrapRedirection).show();
			} else {
				$(idWrapRedirection).hide();
			}
		});

		$(idTextarea).change(function () {
			const passwords = $(this).val().split("\n").map(pass => pass.trim()).filter(pass => pass !== "");
			const checks = [{
				'message': (data) => 'Use 100 characters or fewer for your password',
				'data': (passwords) => passwords.filter((pass) => pass.length > 100)
			}, {
				'message': (data) => data.length > 1 ? 'Please remove space in these passwords:\n' + data.join("\n") : 'Please remove space in this password: ' + data.join(""),
				'data': (passwords) => passwords.filter((pass) => pass.indexOf(" ") !== -1)
			}, {
				'message': (data) => data.length > 1 ? 'Please remove the duplicated passwords:\n' + data.join("\n") : 'Please remove the duplicated password: ' + data.join(""),
				'data': (passwords) => passwords.filter((element, index, pass) => pass.indexOf(element) !== index).filter((element, index, pass) => pass.indexOf(element) === index)
			}];

			let result = false;
			for (let i = 0; i < checks.length; i++) {
				const found = checks[i].data(passwords);
				if (found.length) {
					result = {
						'passwords': found,
						'message': checks[i].message(found)
					};
					break;
				}
			}

			if (result) {
				showWarningMessage(result.message, idSubmitButton, idWrongMessage);
			} else {
				$(idWrongMessage).hide();
				$(idSubmitButton).prop("disabled", false);
			}
		});

		/**
		 * Handle change element for entire site
		 */
		$(idSwitchButton).change(function () {
			const classShowInputPassword = '.ppwp_logic_show_input_password';
			const idComponentRedirection = '.ppw-redirect-url-component';
			if ($(this).prop('checked')) {
				$(classShowInputPassword).show();
				$(idComponentRedirection).show();
				$(idTextarea).attr('required', true);
				if ($(idSwitchExcludePage).prop('checked')) {
					$(idExcludePage).attr('required', true);
				}
			} else {
				$(classShowInputPassword).hide();
				$(idComponentRedirection).hide();
				$(idSubmitButton).prop("disabled", false);
				$(idTextarea).attr('required', false);
				$(idExcludePage).attr('required', false);
			}
		});
		$(idSwitchButton).trigger('change');

		$(idSwitchExcludePage).change(function () {
			const classWrapExcludePage = '.ppwp_wrap_select_exclude_page';
			if ($(this).prop('checked')) {
				$(classWrapExcludePage).show();
				$(idExcludePage).attr('required', true);
			} else {
				$(classWrapExcludePage).hide();
				$(idExcludePage).attr('required', false);
			}
		});
		$(idSwitchExcludePage).trigger('change');
	}

	function _updatePassword(settings, cb, idSubmitButton) {
		const idNonce = '#ppw-entire-site-nonce';
		const _data = {
			action: 'ppw_pro_handle_entire_site_settings',
			settings: settings,
			security_check: $(idNonce).val(),
		};
		ajaxRequest(_data, cb, idSubmitButton);
	}

	function ajaxRequest(_data, cb, idSubmitButton) {
		$(idSubmitButton).prop("disabled", true);
		$.ajax({
			url: ppw_entire_site_data.ajax_url,
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

	function handleTooltip(element) {
		if ($(element)) {
			if ($(element).tooltip) {
				$(element).tooltip({
					position: {
						my: "left bottom-10",
						at: "left top",
					}
				});
			}
		}
	}

})(jQuery);
