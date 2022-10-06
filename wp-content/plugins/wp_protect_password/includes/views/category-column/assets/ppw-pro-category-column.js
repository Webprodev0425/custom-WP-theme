(function( $ ) {
    'use strict';
    $(function() {
        $(".pda-pwd-tbl-category").on('click', (evt) =>{
            var term_id = evt.target.id.split('_')[1];
            var allPostId = $("#all_post_id_in_category_" + term_id).val();
            _updateProtectCategory(allPostId, term_id, function(error) {
                if (error) {
                    console.error(error);
                }
            });
        });
    });

    function changeStatusProtect( ele, status, text) {
        ele.jQueryCheckbox.prop('checked', status);
        ele.jQueryLabel.text(text);
    }

    function _updateProtectCategory(allPostId, term_id, cb) {
        var _data = {
            action: 'ppw_pro_update_category_protect',
            all_post_id: allPostId,
            security_check: $("#pda-password-nonce-category_" + term_id).attr('nonce'),
        };
        const unprotectText = 'Unprotect category';
        const protectCategoryText = 'Protect category';
        const elePasswordProtectionCategory = '#pda-password-protection-category_';
        const eleProtectPasswordCategory = '#pda-protect-password-category_';

        $.ajax({
            url: ppw_entire_site_data.ajax_url,
            type: 'POST',
            data: _data,
            success: function(data) {
                if(data) {
                    const jQueryPasswordProtectionCategory = $(elePasswordProtectionCategory + term_id);
                    const jQueryProtectPasswordCategory = $(eleProtectPasswordCategory + term_id);

                    const allChildCategoryId = ($("#all-child-category-id_" + term_id).val()).split(";");
                    const allParentCategoryId = ($("#all-parent-category-id_" + term_id).val()).split(";");

                    const changeStatusProtectCategoryAndChild = function ( status, text) {
                        changeStatusProtect({
                            jQueryCheckbox: jQueryPasswordProtectionCategory,
                            jQueryLabel: jQueryProtectPasswordCategory,
                        }, status, text);
                        allChildCategoryId.forEach(function(ChildCategoryId) {
                            changeStatusProtect({
                                jQueryCheckbox: $(elePasswordProtectionCategory + ChildCategoryId),
                                jQueryLabel: $(elePasswordProtectionCategory + ChildCategoryId),
                            }, status, text);
                        });
                    };

                    if(jQueryPasswordProtectionCategory.prop('checked')) {
                        changeStatusProtectCategoryAndChild( false, protectCategoryText );
                        allParentCategoryId.forEach(function(ParentCategoryId) {
                            changeStatusProtect({
                                jQueryCheckbox: $(elePasswordProtectionCategory + ParentCategoryId),
                                jQueryLabel: $(eleProtectPasswordCategory + ParentCategoryId),
                            }, false, protectCategoryText);
                        });
                    } else {
                        changeStatusProtectCategoryAndChild( true, unprotectText );
                        data.forEach(function(dt) {
                            if (dt.is_protect) {
                                changeStatusProtect({
                                    jQueryCheckbox: $(elePasswordProtectionCategory + dt.id),
                                    jQueryLabel: $(eleProtectPasswordCategory + dt.id),
                                }, true, unprotectText);
                            }
                        });
                    }
                    toastr.success('Your settings have been updated successfully!', 'Password Protect WordPress Pro', { timeOut: 30 });
                } else {
                    console.log("Failed", data);
                }
                cb();
            },
            error: function(error) {
                $("#submit").prop("disabled", false);
                console.log("Errorsss", error);
                cb(error);
            },
            timeout: 5000
        });
    }
})( jQuery );
