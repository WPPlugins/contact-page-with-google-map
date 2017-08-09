=== Contact Page With Google Map ===
Contributors: Jeremy Muckel, Rob Wiley, Han
Tags: contact, page, contact page, google map, Google Maps, map, maps
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will let users quickly and easily create a contact page with the company hours, address, phone number, and an optional Google Map.

== Description ==
This plugin creates a custom post type for Contact Pages. This allows users to create more than one contact page quickly and easily.

In addition to the default Wordpress editable content, these contact pages can display the company\'s address, phone number, hours of operation, and an embedded Google Map with their location all by simply entering a few details.

If you wish to use the google map feature of this plugin, you must register for a Google Maps Embed API Key. Don't worry, it's quick, easy, and free!

Get an API Key here: https://developers.google.com/maps/documentation/embed/get-api-key

Learn more about Google Maps API Keys here: https://developers.google.com/maps/faq

== Installation ==
1. Upload the un-zipped "zen-contact-page" directory to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Now you are ready to create your first Zen Contact Page by going to "Zen Pages" -> "Add New"

== Frequently Asked Questions ==
Q: Why do I need a Google Maps API Key to use the map feature?
A: Google now requires an API key even if you are just embedding the map with an iframe. Unfortunately, we do not have any control over this and you must sign up for an API key. Google made it easy and free to sign up. You can read more about API keys here: https://developers.google.com/maps/faq

Q: Why do the URLs for the contact pages not match the title of the page?
A: This plugin uses the contact page's "slug" as the URL for syntax reasons. You can change the slug if you enable "slug" in the screen settings menu.

Q: Why is my contact page displaying as a different width than the rest of my theme?
A: This plugin works best with well coded themes. Well coded themes should define the maximum content width site-wide somewhere in functions.php or in the theme files. This value is what is used to set the width of the contact pages. If you determine your max width, and add "$content-width = your_max_width_here;" to functions.php, the issue should be resolved. If you need help, contact a developer or the author of this plugin.

== Screenshots ==
1. This screenshot shows the admin side of the plugin where you can select your hours and enable the Google Map.
2. The plugin looks great in a variety of themes.

== Changelog ==
= 0.1 =
* Initial release.