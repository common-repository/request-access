=== Request Access ===
Contributors: knewton-wp, jontasc
Tags: login, request access, log in, registration, signup, sign up
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 1.4
License: GPL
License URI: http://www.gnu.org/licenses/gpl.html

The Request Access plugin is intended for WordPress website owners who want to allow people to sign up for their website in a controlled way.

== Description ==

The Request Access plugin is intended for WordPress website owners who want to allow people to sign up for their website in a controlled way.

Through this plugin, external users can create accounts which are immediately put into "Pending" state. While an account is pending, it can only access content on your website that you designate as "public". All existing and future site content is private by default except for the required pages (see below).

In your admin area, you can view a list of pending accounts, and then choose to approve or deny their application. The plugin logs all admin actions and allows you to control which account types have access to approve and deny pending accounts. Logs are only visible to admins.

The plugin includes notification emails to administrators (new registrations) and to external users (approved/denied).

== Installation ==

Install the plugin by uploading it to your wp-content/plugins directory and activating it. There will now be a new section available in your admin area, visible only to administrators. The Request Access plugin creates three new pages:

* **Request Access** - This is the main functional area of the plugin where you can view pending accounts and approve or deny them. It is visible to any account types selected in the settings.
* **Log** - This is a log of all the admin actions. It is only visible to adminstrators
* **Options** - This lets you control the options and settings of the plugin

The plugin requires a number of pages to be created, each of which serve a different purpose in the public flow and subsequent approval/denial flows. All of these pages are public by default; you don't need to manually set them as public (though nothing bad will happen if you do):

* **Sign Up page** - This is the page that contains the Sign Up Form. Add the form itself to the page with the shortcode [signup], which allows you to control the text/content that appears before and after the form fields.
* **Log In page** - This is the page that contains the Log In Form. Add the form itself to the page with the shortcode [login], which allows you to control the text/content that appears before and after the form fields.
* **Thanks page** - This is the page the user is redirected to after they complete the signup form. You control all of the content on this page.
* **Pending page** - This is the page the user sees if s/he attempts to log in to the site while their account is still pending. You control all of the content on this page.
* **Denied page** - This is the page the user sees if s/he attempts to log in after their account has been denied. You control all of the content on this page.

The plugin has a few more options that need to be configured before it can be fully used:

* **Notification recipients** - This is a comma separated list of email addresses that should be notified whenever a new user signs up and is pending
* **Approved Email Subject / Body** - This is the email subject/body that is sent to a new user if his/her account has been approved
* **Denied Email Subject / Body** - This is the email subject/body that is sent to users who's account has been denied.
* **Roles allowed to approve/deny pending requests** - This is a list of roles on your website that should be allowed admin access to approve/deny pending users. Check each role that should have access.

== Screenshots ==

1. The main Request Aceess admin screen

2. The Request Access log admin screen

3. The Request Access options admin screen

4. The Request Access page access setting

== Changelog ==

= 1.0 =
* Initial release

= 1.1 =
* Fixed plugin banner image

= 1.4 =
* Fixed remove_cap error (see http://wordpress.org/support/topic/error-313?replies=1)