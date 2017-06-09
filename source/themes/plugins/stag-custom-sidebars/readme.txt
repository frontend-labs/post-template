=== Stag Custom Sidebars ===
Contributors: mauryaratan, codestag
Donate link: http://codest.ag/scs-donate
Tags: sidebars, custom-sidebars, mauryaratan, codestag, shortcodes, widgets
Requires at least: 3.3
Stable tag: 1.0.11
Tested up to: 4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create custom dynamic sidebars and use anywhere with shortcodes.

== Description ==

This plugin adds a button to widgets area to create a new sidebar area which you can later use just about anywhere.

= Usage =
To display the sidebar with shortcode you can use ``[stag_sidebar id="custom-sidebar"]`` where ``id`` is the id of the sidebar that appears in the description area of the respective widget area. You can also pass an additional parameter ``class`` in shortcode to add class to the widget area wrapper on frontend.

[vimeo https://vimeo.com/86626101]

= Import/Export =
We have added compatibility with [Widget Importer & Exporter](https://wordpress.org/plugins/widget-importer-exporter), which gives you the freedom to import and export custom widget areas when moving widgets from one site to another or backing up the widgets.

If you'd like to check out the code and contribute, [join us on GitHub](https://github.com/mauryaratan/stag-custom-sidebars). Pull requests, issues, and plugin recommendations are more than welcome!

*Checkout our finely tuned WordPress themes over at [Codestag](https://codestag.com).*

== Installation ==

= Automatic Installation =

1. Log in and navigate to Plugins &rarr; Add New.
2. Type “Stag Custom Sidebars” into the Search input and click the “Search Widgets” button.
3. Locate the Stag Custom Sidebars in the list of search results and click “Install Now”.
4. Click the “Activate Plugin” link at the bottom of the install screen.
5. Now you can add custom widget areas under Appearance &rarr; Widgets.

= Manual Installation =

1. Download the “Stag Custom Sidebars” plugin from WordPress.org.
2. Unzip the package and move to your plugins directory.
3. Log into WordPress and navigate to the “Plugins” screen.
4. Locate “Stag Custom Sidebars” in the list and click the “Activate” link.
5. Now you can add custom widget areas under Appearance &rarr; Widgets.

== Frequently Asked Questions ==

= Where can I find Stag Custom Sidebars documentation and guides? =
For extending Stag Custom Sidebars you can check the plugin [wiki](https://github.com/mauryaratan/stag-custom-sidebars/wiki) on [Github](https://github.com/mauryaratan/stag-custom-sidebars), where you will find plugin filters and actions in use.

= Will Stag Custom Sidebar work with my theme? =
Yes; Stag Custom Sidebar will work with any theme. However, it may require some styling to match your theme.

= Where can I report bugs or contribute to the project?? =
Bugs can be reported either in our support forum or preferably on the [Stag Custom Sidebars GitHub repository](https://github.com/mauryaratan/stag-custom-sidebars).

== Screenshots ==

1. Add new widget area under Appearance > Widgets
2. As usual, new widget area appears to right of the screen along with a button to delete the widget area with a shortcode in description to use it anywhere.

== Changelog ==

= 1.0.11 - Oct 20, 2014 =
* Fix issue with undefined variables breaking the sidebars

= 1.0.10 - Oct 19, 2014 =
* Fix issue with undefined option keys under customizer when no custom sidebars are present

= 1.0.9 - August 28, 2014 =
* Ensure compatibility with WordPress 4.0
* Fix an issue where plugin caused an error on customizer screen when no custom sidebars are created
* Introduced a new filter, to filter every registered sidebar arguments

= 1.0.8 - June 11, 2014 =
* Added confirmation dialogue when deleting the sidebar area

= 1.0.7 - May 22, 2014 =
* Added compatibilty with Widget Customizer

= 1.0.6 - February 18, 2014 =
* Ability to import/export custom widgets area via [Widget Importer & Exporter](https://wordpress.org/plugins/widget-importer-exporter)
* Performance tweaks

= 1.0.5 - December 21, 2013 =
* Bug Fixes

= 1.0.4 - December 20, 2013 =
* Fixed an issue where deleting a sidebar area caused other sidebar area's widgets to disappear

= 1.0.3 - December 17, 2013 =
* Better integration in WordPress 3.8
* Added backwords compatibilty

= 1.0.2 - December 13, 2013 =
* Fix: Few UI issues with WordPress 3.8
* Improved: Compatibilty with WordPress 3.8
* Provide fallback for older version of WordPress installations

= 1.0.1 - October 31, 2013 =
* Fix: Issue with register_sidebar arguments being overridden
* Fix: Changed opening/closing tags for sidebar wrapper and sidebar widgets

= 1.0 - October 23, 2013 =
* Initial version.

== Upgrade Notice ==

= 1.0.11 - Oct 20, 2014 =
* Fix issue with undefined variables breaking the sidebars
