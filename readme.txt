=== Welcome Email Editor ===
Contributors: davidvongries, seanbarton
Tags: welcome email, wordpress welcome email, welcome email editor, mail, email, new user email, password reminder, lost password, welcome email attachment, mail attachment, email attachment
Requires at least: 4.6
Tested up to: 6.1
Stable tag: 5.0.5
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
* **[Better Admin Bar](https://betteradminbar.com/?utm_source=weed&utm_medium=repository&utm_campaign=bab)** - The plugin to make your clients enjoy WordPress. It replaces the default admin bar to provide the best possible user experience when editing & navigating a website.

== Installation ==
1. Download the welcome-email-editor.zip file to your computer.
1. Unzip the file.
1. Upload the `welcome-email-editor` folder to your `/wp-content/plugins/` directory.
1. Activate the plugin through the *Plugins* menu in WordPress.

== Frequently Asked Questions ==
= Why is the password not included in the welcome email? =
Since version 4.3 the password is no longer sent to the user via email and instead a reset password link is sent instead. This was a controversial decision but it was done for good reason.

== Screenshots ==
1. Welcome Email Editor Settings Page

== Changelog ==
= 5.0.5 | December 07, 2022 =
* Tested up to WordPress 6.1
= 5.0.4 | May 11, 2022 =
* Tweak: Replace [reset_url] with [reset_pass_url] to keep things uniform
* Fixed: Some links were not rendered properly in some cases if Mail Content Type was set to HTML
* Fixed: Wrong textdomains
= 5.0.3 | April 27, 2022 =
* Fixed: Wrong link target in the recommended section
= 5.0.2 | March 02, 2022 =
* Tested up to 5.9
* Tweak: Updated plugin description
= 5.0.1 | June 16, 2021 =
* Fixed: Textdomain issue causing is_readable() warning
= 5.0 | May 22, 2021 =
* Hey there, David here :) We have taken over the Welcome Email Editor project from our friend Sean Barton. This is our initial release - Apart from some minor improvements, no new features were added. With this release we have reviewed & rewritten the codebase from ground up and greatly improved the settings page design. Stay tuned for more.
