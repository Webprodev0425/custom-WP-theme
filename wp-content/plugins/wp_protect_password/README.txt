=== WordPress Password Protect Page Plugin ===
Contributors: gaupoit, rexhoang, ppwp, buildwps
Donate link: https://passwordprotectwp.com/features/?utm_source=wp.org&utm_medium=post&utm_campaign=plugin-link
Tags: password protect, password page, protect page, wordpress protection, login
Requires at least: 4.7
Tested up to: 5.4
Stable tag: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Password protect WordPress pages and posts by user roles or with multiple passwords; protect your entire website with a single password

== Description ==

Password protect WordPress pages and posts by user roles or with multiple passwords; protect your entire website with a single password.

This plugin does not protect images or uploaded files so if you attach the media files to the protected pages or posts, they are still accessible to anyone with the link. Use [Prevent Direct Access Gold](https://preventdirectaccess.com/features/?utm_source=wp.org&utm_medium=ref-link&utm_campaign=password-protect-page-lite) to block their direct file URL access.

Please note that the passwords will be stored in the post meta and this plugin will set a cookie to allow access the protected pages or posts.

= An Inside Look at Password Protect WordPress Pro =
https://www.youtube.com/watch?v=myYsKXZyNwc

== Changelog ==

= 1.2.2: April 28, 2020 =

- [Improvement] Implement the message service that extensions can re-use

- [Improvement] Improve the way to display tabs under settings page

- [Improvement] Allow users to select a specific expiry date instead of set expiration time

- [Feature] Allow searching "label" field under password popup

- [Feature] Setting option that allows shortcode global passwords to unlock parts of multiple pages at once

- [BugFix] Add inline <style></style for sidewide shortcode

- [BugFix] Cannot use "show the password" in ppwp_sitewide shortcode

- [BugFix] Duplicated assets when loading entire side form

- [BugFix] Sub-pages still show up on sitemap when the parent is protected

- [BugFix] Redirect URL not working with sitewide shortcode

= 1.2.1: March 31, 2020 =

- [Improvement] Upgrade text in plugin

- [Improvement] Apply hook to share sitewide function

- [BugFix] "Show password" button of ppwp-swf shortcode doesn't work

- [Improvement] Restrict 3-site & 10-site license from being installed in multisite mode

= 1.2.0: March 19, 2020 =

- [Feature] Customize Login/Entire Site Password Form with WP Customizer

- [Feature] Generate a shortcode for sidewide password form

- [Feature] Exclude entire post types from sitewide protection

- [Feature] Create basic public APIs for users

- [Improvement] Display Email Marketing type for password

- [BugFix] Integrate with PDA Gold - doesn't work with scaled images

- [BugFix] Integrate with PDA Gold - Whitelisted roles can't see files without FAP

= 1.1.6: February 11, 2020 =

- [Improvement] Add hook to track password entire site

- [Feature] Handle private links created by PDA Gold within a session vs using download_limit = 1

- [Improvement] Extent feature "Protected Content Visibility" from Free version

- [BugFix] Not work with caching

- [Feature] PCP shortcode - Add pwd attribute to select passwords from settings

- [BugFix] Miss p4v8 folder

- [BugFix] Conflict with PPWP because of using template_reditect

- [Improvement] Integrate with PDA Gold not work when using master password

- [Feature] Manage PCP Shortcode passwords under settings page

- [Feature] Remove page builders text under Shortcode tab to avoid duplicate with Free version

- [Improvement] Allow users to translate ppwp login form

- [Improvement] Add hook that helps to track the PCP passwords data

= 1.1.5.2: December 26, 2019 =

- [Improvement] Create a sidebar for Pro

- [Feature] Add hook to get function (Elementor) from Free

- [Feature] Add hook to get function (Beaver Builder) from Free

- [Feature] Master passwords Extends hook for post-types

- [Improvement] Select which columns to display on the popup

- [Feature] Add "Password Protect" button link under post title

- [Dev] Update plugin-update-checker to 4.8

- [Improvement] Hide password protected content - show content with whitelisted roles

- [BugFix] Fix title when enabling entire site & update ppwp

- [BugFix] Conflict UI with Admin Columns Pro

- [Improvement] Add description for Customizer under Password Form section

- [Feature] Display password protection column with Publish status only

= 1.1.5.1: December 3, 2019 =

- [BugFix] Whitelisted Roles in Elementor block doesn't work

- [Improvement] Improve the hook ppwp_post_password_required that works with extensions PPWP Access Levels and PPWP Group Protection

- [Improvement] Change password status and apply new logic for expired passwords

= 1.1.5: November 18, 2019 =

- [Feature] Password protect content in custom fields

- [Feature] Password protect files ( Phase 1 )

- [Improvement] Add new attributes for short code

- [Improvement] Shortcode - Support custom post type

- [Improvement] Improve UI to compatible with WP 5.3

- [Improvement] Add Protect Files section under settings page

- [Improvement] Add "ppwp-sitewide-protection" into body class for styling purposes

- [Feature] Shortcode - Integrate with visual Page Builders

- [Feature] Add hook to track password for entire site

- [BugFix] PPWP Pro: Fix popup flash of hidden content

= 1.1.4.1: October 31, 2019 =

- [HotFix] In home page, enter the password for any post, the others also unlock

= 1.1.4: October 31, 2019 =

- [Improvement] Handle private links when integrating with PDA Gold
- [Feature] Set redirect URLs for specific entire-site passwords (Phase 1)
- [Improvement] Handle password form WooCommerce product when remove "woocommerce_before_single_product"
- [Improvement] Performance issue when use post_password_required
- [Improvement] Apply hook to check condition handle S&R from PDA Gold
- [Improvement] Optimize capacity for Front-End
- [BugFix] Save wrong cookies for roles password
- [BugFix] Issue when integration with PDA Gold
- [Improvement] Display error message when integrating with PDA version 3.1.2 and 3.1.2.1

= 1.1.3: September 24, 2019 =

- [Feature] Allow users to skip entering passwords for the 1st time via URL parameters
- [Feature] Customize entire site login form
- [Improvement] Show error message if enter more than 255 characters under PP Private Pages
- [Improvement] Update the color of error message in password form to #dc3232
- [Improvement] Integrate with PDA Gold - return to pda link if users have permission
- [Improvement] Change Whitelist Roles -> Whitelisted Roles
- [Improvement] Compile css into one bundle file in Settings Page
- [Improvement] Improve performance for pop-up
- [BugFix] Conflict with Beaver Builder -> show error message when accessing 404 page

= 1.1.1: August 23, 2010 =

- [Feature] Set multiple passwords for entire site
- [Feature] Make Whitelist Roles work with "global site password protection"
- [Improvement] Show switch button under metabox of child pages
- [BugFix] The post reverts the protection status after click update the post
- [BugFix] We will get error message when multiple clicks button "Save changes" at the same time
- [BugFix] Can't set password = 0 under settings page

= 1.1.0: August 08, 2019 =

- [Improvement] Use update server system
- [Improvement] Revamp architecture to improve performance
- [Improvement] Resolve compatibility with Cache Plugins
- [BugFix] Password can be case-sensitive
- [BugFix] Support user having multiple roles
- [BugFix] Require user to key in license
- [Feature] Display show/hide password form
- [Feature] Add custom GET param when user enter password successfully

= 1.0.10.2: June 20, 2019 =

- [Feature] PPWP: Add "Post Type Protection" option under Settings Page - UI - Database Saving
- [Feature] PPWP: Display Password Protection column under CTP page
- [Feature] PPWP: Hide default password protect function
- [Feature] Migrate default password to our popup when PPWP Pro is active
- [Feature] PPWP: Improve feature migrate default password for Custom Post Type
- [Improvement] PPWP: Improve feature support Custom Post Type
- [BugFix] PPWP Pro: Notice: Undefined offset: 0

= 1.0.10.1: May 28, 2019 =

- [HotFix] Uncaught Error: Class 'WP_Protect_Password_Service' not found when license has never entered
- [BugFix] PPWP Gold: Show 2 password fields in protected product page
- [BugFix] PPWP Gold: Should remove Products page from Exclude field when users deactivate WooCommerce Plugin

= 1.0.10: May 10, 2019 =

- [Feature] Disallow search engine to index the protected pages or posts
- [Feature] Allow Changing Text on Password Protected Page
- [Improvement] post_password_required doesn't work with our plugin
- [Improvement] Improve UI of Settings Page
- [BugFix] The text of button link to open popup is inconsistent

= 1.0.9: April 18, 2019 =

- [Feature] PPWP Gold: Exclude certain pages from our Global site protection
- [Feature] Migrate default password when activating gold version and remove the function set default password of WP
- [Feature] Migrate data from Free version to Gold version
- [Feature] Show a warning message when users deactivate our plugins

= 1.0.8.1: April 10, 2019 =

- [BugFix] Password Protect Entire Site is enabled but the password field is empty
- [BugFix] Update license type in Setting Page
- [BugFix] Password Protect Entire Site doesn't work if Cookies Expiration Time is more than 9999 days
- [BugFix] WP logo is missing on sub pages
- [BugFix] Password Protect Private Pages incorrect when users enter special characters
- [BugFix] Error message does not display when users enter the wrong password
- [Improvement] Show notice not work with sites use caching
- [Improvement] Improve UI Settings Page

= 1.0.8: January 30, 2019 =

- [Improvement] Add a setting option that user can keep the current option, data or license when they uninstall the plugin
- [BugFix] More-than-30-characters password isn't shown under popup
- [Improvement] Should show error message when entering wrong password
- [Improvement] Add permissions to API
- [Improvement] Should hide the General and Advance tabs when the license is not valid and has never entered
- [BugFix] Duplicate default password when re-protect page/post
- [BugFix] Fix "quick edit" in Protect Password

= 1.0.7.1: December 12, 2018 =

- [BugFix] Quick fix for the error that cannot load the service class for the new customers

= 1.0.7: December 11, 2018 =

- [Feature] Categorize new passwords by roles or global
- [Feature] Should be able to edit Usage Limit when clicking Edit under popup
- [Improvement] Add metabox to edit the post for user set password
- [Improvement] Update UI & Text

= 1.0.6.2: November 21, 2018 =

- [Improvement] Allows users to add & change error messages when entering wrong passwords

= 1.0.6.1: November 21, 2018 =

- [Improvement] Set default option Password Protect Child Pages is on

= 1.0.6: November 20, 2018 =

- [Improvement] Create Manager Passwords popup for child pages
- [Feature] Support other post types including Woo Products

= 1.0.5: November 16, 2018 =

- [BugFix] PPWP: Can't open popup after Quick Edit
- [Improvement] Customize UI for function "password entire site"
- [Feature] Add option set password for page and post in setting (UI)
- [Feature] Use the same password form with this WordPress filter (tested by developers)
- [Feature] PPWP: Protect multiple pages with the same password
- [Improvement] Prevent password duplication between Global (shared) password with the other types in a post/page
- [Improvement] Prevent password duplication between Role password type  with the other types in a post/page

= 1.0.4.2: November 7th, 2018 =

- [BugFix] Database cannot update

= 1.0.4.1: November 7th, 2018 =

- [BugFix] Add high priority for upgrade plugin complete hook

= 1.0.4: November 6th, 2018 =

- [BugFix] Password protect entire site does not work
- [Improvement] Should remove "Password protected by user roles" option on child pages
- [Improvement] Should remove space when creating Password protect by user roles
- [BugFix] Settings option: Password's cookies expiration
- [Improvement] Should hide "New Password" if page/post is unprotected
- [Feature] Expire passwords by date or time
- [BugFix] Password Expiry changes to 7AM after editting
- [BugFix] Page/post is unprotected although creating password successfully
- [Feature] All protected files in content will be accessible after unlocking a password protected content
- [Improvement] Improve settings UI
- [BugFix] PPWP & ActiveCampaign: Dropdown display bug

= 1.0.3.1 =

- [BugFix] Add a css class to fix Yoast bug that overriding the react tab's css library

