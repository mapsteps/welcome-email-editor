=== Swift SMTP (formerly Welcome Email Editor) ===
Contributors: davidvongries
Tags: WP Mail SMTP, Welcome Email Editor, Custom SMTP, SMTP, WordPress Email
Requires at least: 4.6
Tested up to: 6.6
Stable tag: 6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
**Swift SMTP** is a free & simple SMTP Plugin for WordPress.

Struggeling with emails not being delivered from your WordPress website? Look no further.

= 📤 Custom SMTP Settings =
Swift SMTP allows you to configure custom SMTP settings for your WordPress site, ensuring more reliable sending and delivery through your preferred service.

* Set "From" email address
* Set "From" name
* Define email content type (HTML or plain text)
* Set SMTP host
* Set up SMTP encryption & port (SSL or TSL)
* Set up SMTP authentification through username & password

= 💾 Email Logging (New!) =
* Log all outgoing emails

= 📨 Customize WordPress Welcome Emails =
When a user is added to or signs up for a website, WordPress sends notifications to both the site administrator and the new user. This plugin allows you to customize & change those emails.

* Change email subject
* Change email content
* Add an attachment
* Change "Reply-To" email address & name
* & more

= 🔐 Change WordPress Reset Password Email =
Swift SMTP also lets you customize the default **Forgot Password** email in WordPress.

* Change email subject
* Change email content

= What's next? =
If you like Swift SMTP, make sure to check out our other products:

* **[Ultimate Dashboard](https://ultimatedashboard.io/?utm_source=weed&utm_medium=repository&utm_campaign=udb)** - The #1 WordPress plugin to customize your WordPress dashboard and admin area.
* **[Page Builder Framework](https://wp-pagebuilderframework.com/?utm_source=weed&utm_medium=repository&utm_campaign=wpbf)** - A fast & minimalistic WordPress theme designed for the new WordPress era.
* **[Better Admin Bar](https://betteradminbar.com/?utm_source=weed&utm_medium=repository&utm_campaign=bab)** - The plugin to make your clients enjoy WordPress. It replaces the default admin bar to provide the best possible user experience when editing & navigating a website.

== Installation ==
1. Download the welcome-email-editor.zip file to your computer.
1. Unzip the file.
1. Upload the `welcome-email-editor` folder to your `/wp-content/plugins/` directory.
1. Activate the plugin through the *Plugins* menu in WordPress.

== Frequently Asked Questions ==
= Why is the Password not included in the Welcome Email? =
Since version 4.3, the password is no longer sent to the user via email, and instead, a reset password link is sent. This was a controversial decision, but it was made for a good reason.

== Screenshots ==
1. Swift SMTP Settings

== Changelog ==
= 6.2 | September 03, 2024 =
* New: Email Logging
* Tested up to WordPress 6.6
= 6.1.2 | May 10, 2024 =
* Tested up to WordPress 6.5
= 6.1.1 | January 08, 2024 =
* New: Setting to force sender name & email
* Tweak: Add additional check to test email to be able to skip authentification
= 6.1 | January 08, 2024 =
* Tweak: We are now using Plain Text instead of HTML as the default content type for the welcome emails & smtp settings as this better aligns with WordPress' core default emails.
= 6.0.1 | January 03, 2024 =
* New: [logged_in] & [not_logged_in] tags for Welcome Email Editor
* Tweak: Visual feedback when sending test emails in Welcome Email Editor
* Tweak: Make sure the Welcome Emails mimick WordPress' default emails
* Tweak: Improved email template when triggering a test email from the SMTP settings tab
* Fixed: Multiple recipients didn't work in Welcome Email Editor
= 6.0 | December 30, 2023 =
* **Welcome Email Editor is now Swift SMTP!** We've revamped Welcome Email Editor to bring you an easy-to-use & free SMTP plugin for WordPress!
= 5.0.7 | December 12, 2023 =
* Fixed: Minor security issue
= 5.0.6 | December 01, 2023 =
* Tested up to WordPress 6.4
* Fixed: Security issue
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
