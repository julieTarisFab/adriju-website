=== Plugin Name ===
Contributors: erichmond
Donate link: http://www.squareonemd.co.uk/
Tags: Instagram, tag, hashtag
Requires at least: 4.5
Tested up to: 4.9.2
Stable tag: 1.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin creates a feed of Instagram images on your WordPress site based on a specific Instagram hashtag.

== Description ==

Simply register your Instagram application to authenticate and authorize your requests. Then whenever an image is posted to Instagram with a specific hashtag this plugin will request only those images and render them in your WordPress installation.

Please read the Instagram terms for your own usage at http://Instagram.com/about/legal/terms/.

== Installation ==

<iframe width="560" height="315" src="https://www.youtube.com/embed/siGLieDi73Q" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

To take full advantage of this plugin you'll need an Instagram account and a registered
Instagram application. Simply go to http://Instagram.com/developer/ and once your application
has been registered you will be provided with a CLIENT ID and a CLIENT SECRET. You will use
these to authenticate the requests from your site to Instagram.

To install the plugin:

1. Upload `insta-grab-gram` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to http://Instagram.com/developer/ and click the button that says "Register Your Application"
4. Fill in the details requested by Instagram, these will be thing like Application Name, Description, Website and redirect_uri.
5. Once complete you will be given a CLIENT ID and a CLIENT SECRET.
6. On the WordPress site where you have this plugin installed simply copy and paste the CLIENT ID, CLIENT SECRET and WEBSITE URI under the plugins settings that can be found in from the Dashboard > Settings > Instagrabagram settings page.
7. Then click Authorise Instagram.
8. Once complete place `<?php do_action('insta_grab_a_gram'); ?>` where you want your feed to appear in one of your theme templates files.

== Frequently Asked Questions ==

= I don't have an instagam account =

Simply go to http://Instagram.com and register your users account.

= Where can I find my CLIENT ID and CLIENT SECRET =

Once logged in to your Instagram account go to http://Instagram.com/developer/ and register your application,
once activated you will be given the CLIENT ID and CLIENT SECRET in your account, you can manage mulitple applications.

== Screenshots ==

1. How it looks in the front end
2. Really simple settings
3. Include your own filters and hooks for flexibility

== Changelog ==

= 1.2.2 =

* Fatal Error fixed
* string sanitisation
* Compatibility test

= 1.2.2 =

* Compatibility test

= 1.2.2 =

* Authoring link changes
* Tab conflict with other plugins
* Tabs cleaned up

= 1.1.9 =

* Authoring link amended back to basic to avoid public_content

= 1.1.8 =

* Authoring link amend & missing image

= 1.1.7 =

* Authoring link amend

= 1.0.7 =

* Compatibility changed to comply with instagram's new endpoints.
* Instagram authorising added.

= 1.0.6 =

* Compatibility testing.
* Link to Instagram setting update.

= 1.0.5 =

* Improved installation instructions in the admin settings page.

= 1.0.4 =

* A fix to limited calls to Instagram, must be no more than 20 
* Some minor superficial edits in the admin, typos and aesthetics  

= 1.0.3 =

* New actions and filter hooks added 

= 1.0.2 =

* Readme changes

= 1.0.1 =

* Improved settings interface.
* More detailed installation steps.

= 1.0 =

* First release.

== Upgrade Notice ==

== A brief Markdown Example ==

Settings include:

1. Your chosen hashtag
2. And the amount of images requested