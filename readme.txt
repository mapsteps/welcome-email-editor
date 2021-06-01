=== Welcome Email Editor ===
Contributors: davidvongries, seanbarton
Tags: welcome email, wordpress welcome email, welcome email editor, mail, email, new user email, password reminder, lost password, welcome email attachment, mail attachment, email attachment
Requires at least: 4.9
Tested up to: 5.7
Stable tag: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
**Welcome Email Editor** is a clean, simple & lightweight plugin which allows you to **change the default WordPress Welcome & Forgot Password emails**.

Whenever a user is added to a website, WordPress sends a notification to the site admin & the user that was added (or signed up) to the website. This plugin allows you to customize those emails.

= Change the WordPress Welcome Emails =
The Welcome Email Editor plugin allows you to change the default welcome emails for both, the user & site administrator individually.

* Change the email subject
* Change the email content
* Add an attachment
* Change "Reply-To" email address
* & more

= Change the WordPress Forgot Password emails =
Welcome Email Editor also allows you to change the default **Forgot Password** email in WordPress.

* Change the email subject
* Change the email content

= General Settings =
In addition to the settings above, there are general settings which will apply to the welcome & forgot password emails.

* Change *From Email* address
* Change *From Name*
* Change Content Type from Plain Text to HTML

= What's next? =
If you like Welcome Email Editor, make sure to check out our other products:

* **[Ultimate Dashboard](https://ultimatedashboard.io/?utm_source=weed&utm_medium=repository&utm_campaign=udb)** - The #1 WordPress plugin to customize your WordPress dashboard and admin area.
* **[Page Builder Framework](https://wp-pagebuilderframework.com/?utm_source=weed&utm_medium=repository&utm_campaign=wpbf)** - A fast & minimalistic WordPress theme designed for the new WordPress era.
* **[WP Swift Control](https://wpswiftcontrol.com/?utm_source=weed&utm_medium=repository&utm_campaign=swiftcontrol)** - The plugin to make your clients enjoy WordPress. It replaces the default admin bar to provide the best possible user experience when editing & navigating a website.

== Installation ==
1. Download the welcome-email-editor.zip file to your computer.
1. Unzip the file.
1. Upload the `welcome-email-editor` folder to your `/wp-content/plugins/` directory.
1. Activate the plugin through the *Plugins* menu in WordPress.

== Frequently Asked Questions ==
= Why is the password not included in the welcome email? =
Since version 4.3 the password is no longer sent to the user via email and instead a reset password link is sent instead. This was a controversial decision but it was done for good reason.

= Can I add my own hooks? =
There are two ways to do this. You can use the filter to get the email content and parse it yourself or the easier method would be to use the 'sb_we_replace_array' filter which expects an array which the plugin will parse. See below for examples:

`$admin_message = apply_filters('sb_we_email_admin_message', $admin_message, $settings, $user_id);`
`$admin_subject = apply_filters('sb_we_email_admin_subject', $admin_subject, $settings, $user_id);`
`$user_subject = apply_filters('sb_we_email_subject', $user_subject, $settings, $user_id);`
`$user_message = apply_filters('sb_we_email_message', $user_message, $settings, $user_id);`

The above code is from the plugin. You can edit the admin and user subject lines and body contents in any way you like. I won't explain any further as this is either something you know or you don't. The following method is easier:

`$user_message_replace = apply_filters('sb_we_replace_array', array(), $user_id, $settings);`

This method passes a filter an array and you can write in your own code to add hooks to the array for parsing. You can do the following:

`add_filter('sb_we_replace_array', 'my_sb_we_replace_array', 10, 3);

function my_sb_we_replace_array($hooks, $user_id, $settings) {
    $hooks['my_hook'] = 'test';
    
    return $hooks;
}`

This will allow the plugin to process a hook called [my_hook] and replace it with the word test. The user id is passed to the function as well so you can get information about the user and replace that in as well as the settings array from the welcome email editor plugin. If you need help with this please get in touch.

== Screenshots ==
1. Welcome Email Editor Settings Page

== Changelog ==
= 5.0 | May 22, 2021 =
* Hey there, David here :) We have taken over the Welcome Email Editor project from our friend Sean Barton. This is our initial release - No new features were added. With this release we have reviewed & rewritten the codebase from ground up and greatly improved the settings page design. Stay tuned for more.
