const ppwpRowAction = (function ($) {
	return {
		handleOnClickRowAction
	};
	function handleOnClickRowAction(postId) {
		let action = $('#ppw-protect-post_' + postId);
		if (!action) {
			return;
		}
		const { plugin_name } = ppw_row_action_data;
		const status = action.attr('data-ppw-status');
		if (typeof status === 'undefined') {
			return;
		}
		const textProtect = action.text(); // Get text in current action
		action.css('pointer-events', 'none'); // Disable button protect/unprotect when request
		const titleProtect = action.attr('title'); // Get title in current action
		const postType = titleProtect.split(' ').pop(); // Get post type in title
		action.text(textProtect + 'ing...'); // Change text for button when request
		// Handle protect/unprotect page/post
		protectOrUnprotect(postId, status, function (result, error) {
			action.css('pointer-events', 'auto'); // Enable button protect/unprotect after request
			if (error) {
				action.text(textProtect);
				if (400 === error.status) {
					toastr.error(error.responseJSON.message, plugin_name);
				} else {
					toastr.error( 'Oops! Something went wrong. Please reload the page and try again.', plugin_name);
				}
				return;
			}
			const { server_status, list_child_pages, message } = result;
			handleActionSuccess(action, postType, server_status, list_child_pages, postId);
			toastr.success(message, plugin_name);
		});
	}

	function handleActionSuccess (action, postType, serverStatus, listChildPages, postId) {
		action.attr('data-ppw-status', serverStatus); // Change text for button
		const textProtectId = '#pda-protect-password_'; // Get ID button protect
		const iconProtectId = '#ppw_wrap_icon_protect_'; // Get ID icon protect
		const protectionStatus = {
			protect: {
				status: 1,
				label: 'Protect',
			},
			unprotect: {
				status: 0,
				label: 'Unprotect',
			},
		};
		if (protectionStatus.protect.status === serverStatus) {
			action.text(protectionStatus.protect.label); // Change text for button
			handleUnprotectedIcon(action, postType, iconProtectId, textProtectId, postId, listChildPages);
		} else {
			action.text(protectionStatus.unprotect.label); // Change text for button
			handleProtectedIcon(action, postType, iconProtectId, textProtectId, postId, listChildPages);
		}
	}

	function handleUnprotectedIcon (action, postType, iconProtectId, textProtectId, postId, listChildPages) {
		action.attr('title', 'Password protect this ' + postType); // Change title for button in row action
		$(textProtectId + postId).text('Password protect'); // Change text for icon unprotected
		const $icon = $(iconProtectId + postId);
		const htmlIcon = '<i class="dashicons dashicons-unlock"></i> unprotected';
		// Remove and add class to change background color for icon unprotected
		$icon.removeClass('ppw_protected_color');
		$icon.addClass('ppw_unprotected_color');
		$icon.html(htmlIcon); // Change dashicons from lock to unlock
		listChildPages.forEach(function (ChildPageID) {
			const iconChildPage = iconProtectId + ChildPageID;
			// Remove and add class to change background color for icon unprotected(case protect child page)
			$(iconChildPage).removeClass('ppw_protected_color');
			$(iconChildPage).addClass('ppw_unprotected_color');
			$(iconChildPage).html(htmlIcon); // Change dashicons from lock to unlock(case protect child page)
		});
	}

	function handleProtectedIcon (action, postType, iconProtectId, textProtectId, postId, listChildPages) {
		action.attr('title', 'Unprotect this ' + postType); // Change title for button in row action
		$(textProtectId + postId).text('Manage passwords'); // Change text for icon protected
		const $icon = $(iconProtectId + postId);
		const iconHtml = '<i class="dashicons dashicons-lock"></i> protected';
		// Remove and add class to change background color for icon protected
		$icon.removeClass('ppw_unprotected_color');
		$icon.addClass('ppw_protected_color');
		$icon.html(iconHtml); // Change dashicons from unlock to lock
		listChildPages.forEach(function (ChildPageID) {
			const $iconChildPage = $(iconProtectId + ChildPageID);
			// Remove and add class to change background color for icon protected(case protect child page)
			$iconChildPage.removeClass('ppw_unprotected_color');
			$iconChildPage.addClass('ppw_protected_color');
			$iconChildPage.html(iconHtml); // Change dashicons from unlock to lock(case protect child page)
		});
	}

	function protectOrUnprotect (postId, status, cb) {
		const _data = {
			postId,
			status,
			action: 'ppw_pro_update_post_status',
		};
		ajaxRequest(_data, cb);
	}

	function ajaxRequest (_data, cb) {
		const {ajax_url, nonce} = ppw_row_action_data;
		$.ajax({
			url: ajax_url,
			type: 'POST',
			data: _data,
			timeout: 5000,
			headers: {
				'X-WP-Nonce': nonce,
			},
			success: function (data) {
				cb(data, null);
			},
			error: function (error) {
				cb(null, error);
			},
		});
	}
})(jQuery);
