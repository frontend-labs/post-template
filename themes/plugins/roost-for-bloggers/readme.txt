=== Roost Web Push for Safari ===
Contributors: noticesoftware, danstever
Tags: safari, push, push notifications, web push notifications, Mavericks, mobile, web push, roost, roost.me, roost_me, goroost, goroost.com Post, plugin, admin, posts, page, links, widget, ajax, social, wordpress, dashboard, news, notifications, services
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 2.1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Drive traffic to your website with Safari Mavericks push notifications and Roost.

== Description ==

[Browser Push by Roost](http://goroost.com/) - Fundamentally changing the way people consume content on the web.

Push Plugin Features:

* **Safari Web Push** - Take advantage of web push (Desktop Push Notifications) on OS X Mavericks.
* **Notification Prompt Controls** - Decide how and when the subscription prompt is shown to your visitors.
* **Custom Notification Headlines** - Set a custom headline for your notification to be used instead of a post title.
* **bbPress Subscriptions** - Allows site visitors to subscribe to forums, topics, or individual messages when posting replies.
* **Detailed Analytics** - *Charts* with detailed metrics about your visitors straight to your WordPress dashboard.
* **Auto Notifications** - *Now with support for posting via email and from the WordPress mobile app!* We take the work out of it. Automatically send notifications to your readers when creating a new post.
* **Manual Post Mode** - Send notifications for individual posts when creating. Just check the box.
* **Scheduled Post Notifications** - Not posting now? Don't worry. Your alerts will go out when your content posts.

**Free** Roost account included. No setup fees, no surprises, and no limitations on your site visitors.

[youtube http://www.youtube.com/watch?v=DxElgkUsrCA]

== Installation ==

= From your WordPress dashboard =
1. Visit 'Plugins > Add New'
2. Search for 'Roost'
3. Activate Roost from your Plugins page
4. (a) Create your Free Roost for Bloggers account - (Click on "sign up for free") OR (b) Log In an Existing Account - Click "Log In" and enter your username and password

= From WordPress.org =
1. Download Roost For Bloggers Wordpress Plugin.
2. Upload the 'Roost' directory to your '/wp-content/plugins/' directory.
3. Activate Roost For Bloggers from your Plugins page.
4. (a) Create your Free Roost for Bloggers account - (Click on "sign up for free") OR (b) Log In an Existing Account - Click "Log In" and enter your username and password

= Using Features =

**Safari Web Push**
To use Safari Web Push, simply activate the *Roost for Bloggers* plugin. When your site is viewed in a push-enabled browser, the browser will prompt for permission. No additional setup is needed.

**Notification Prompt Control**
Control how and when you display the subscription prompt to your site visitors. From the settings tab, you can set a minimum number of visits, or create a link or button to trigger the prompt. When a button or link is used, if the browser is not able to receive push notifications, we will automatically hide the link or button.

**Custom Notification Headlines**
Use custom text for your notification headline by setting it underneath the standard post title when creating your content. If you set a custom headline, it will be used for your notification. Not setting a headline will default to your post title.

**bbPress**
With the bbPress extension for Roost, an additional subscribe option will appear next to the default bbPress subscription links. (This does NOT replace traditional subscription methods, only adds a new browser push channel.) Options include Forum subscriptions, Topic subscriptions, and subscribing to your individual posts. Notifications are sent about new content **only** to the visitors that have subscribed to the forum, topic, or post.

**Auto Push**
Auto Push is what makes this plugin stellar. When you create a new post, your post's title, link, and featured image (if one is attached) will be sent to all of your subscribed readers. That's it. Really. You don't have to do anything else. (It works just like magic.) When enabled, an auto push override check box is also placed on your post's admin page. If for some reason you do not want a notification to go out for that post, just check the box when publishing.

**Manual Post Mode**
With *Auto Push* disabled, a checkbox will appear just above the Publish button on your post page. Simply check the box *Send Notification with Roost* and a notification will be sent when publishing.

**Send Manual Alert**
You can send a manual alert to all subscribed users by entering your message text in the "Notification Text" box and a link in the "Notification Link" box.

**Activate all Roost Features**
When checked, your site will automatically receive the newest (and coolest) features from Roost as we roll them out. (Like Chrome Web Push. *cough*)

**Roost Analytics & Roost JS**
Detailed metrics are provided in the dashboard. These metrics include subscribes, notifications sent, reads, total page views, and more.

== Frequently Asked Questions ==

= What does Roost For Bloggers cost? =
**It's free!** No setup fees, no surprises, no limitations on your site visitors or subscribers.

= Free? Really? What's the catch? =
No catch! We believe in providing a great service and making it accessible to everyone. So... It's free. (Up to 100,000 messages. If you hit that... We'll need to talk.)

= Is it really this easy to use Safari Web Push on my site? =
Yup! Cool right?

= Do I need and Apple Developer Account or Google Play Developer account? =
Nah. We've got you covered.

= Do you support Google Chrome or Firefox for desktop push? =
Almost. We will be releasing support for Google Chrome very soon. Firefox will follow. To take advantage of them as we include support, make sure to have *Activate all Roost Features* checked.

= Do my readers / subscribers have to create an account with Roost? =
Nope. Just you.


== Screenshots ==

1. One-Click to subscribe for visitors to your site. Easy peasy!
2. Notifications will be delivered in the top-right corner of your subscribers screen.
3. Create your Roost Account. (It's Free!)
4. Or log in to your existing Roost account.
5. Roost plugin from the WordPress dashboard showing analytics.
6. Powerful control on how your visitors can subscribe and how your notifications are sent.

== Changelog ==
= 2.1.8 =
* Fixed (encoded) URL that was causing W3C validation error
* Removed outdated metrics from the Roost dashboard inside WP Admin

= 2.1.7 =
* Major updates to roost.js (Includes performance boost and new APIs)
* Custom headline field in post screen moved to a meta box instead of injected textfield

= 2.1.6 =
* Added 30-day to Roost stats on graph (Yay for history!)
* Bugfix - Fixed bug that prevented notifications for Scheduled Posts
* Bugfix - Show custom headline only when logged into Roost
* 'roost' prefixed some classes / ids
* Hid custom post meta fields on post pages
* Code Cleanup

= 2.1.5 =
* Control for Prompt - auto prompt, after number of visits, off, on button / link click
* Added custom headline option for notification when publishing a post
* Cleared for takeoff with WordPress v4.0
* Bugfix - Roost JS now only loaded if logged into plugin.
* Bugfix - Total notifications sent in dashboard now really total notifications *Palm to Face*
* Code Cleanup

= 2.1.4 =
* Bugfix - Fixed bug that prevented stats from displaying when WordPress admin accessed via HTTPS
* Bugfix - Fixed bug that showed blank stats / setting screen on invalid login attempt
* Code Cleanup

= 2.1.3 =
* RoostJS now served from CDN (BAM! Blazing fast! You didn't see that one did you?)
* Removed mobile app setting
* Updated API URLs and references to goroost.com (Go Go Gadget Roosters... Or something like that.)
* Fixed bug when manual sending notification to eliminate multiple messages.

= 2.1.2 =
* Fixed bug that may have prevented new sites to Roost from being able to send manual notifications from the dashboard
* Fixed bug with charts not loading correct data on tab click

= 2.1.1 =
* Fixed bug that prevented Roost admin screen from showing in PHP versions older than 5.3.0
* Some more code cleanup

= 2.1 =
* Added special support for bbPress subscriptions
* Charts!!! Lots of Charts...
* Plugin redesign - Uses tabs to organize sections
* Changed how the Roost JS script is loaded - Now loading asynchronously
* Included option to send push notifications when publishing posts without using the "Auto Push" feature
* Simplified account creation (in the plugin and on Roost website) and included uploading a logo and naming your site - Auto-login on return
* Added support for signing into the plugin with Facebook, Twitter, and Google+ (SSO)
* Removed upgrade path from v1.0 - Should not impact anyone at this point
* Changed logo and username by log out (in the top right corner) to account email - Context makes more sense with multi-config accounts
* Fixed bug that removed Notification Override setting when doing a Quick Edit
* Put in null check when sending notifications - Prevents sending null alert with can cause errors
* Code cleanup

= 2.0.5 =
* Now supports Auto Push when posting from the WordPress mobile app and by email via Jetpack by WordPress.com
* New first time login screen
* Roost API check on activation to see if URL is reachable
* Code cleanup

= 2.0.4 =
* Changed hard limit to soft character limit on manual notifications
* Restricted notifications to a "post" post type only
* Added settings link on plugin page
* Roost stats now cached to improve admin page performance
* Fixed bug that caused resending notifications when using "Quick Edit" to modify posts
* Removed shortcodes
* Code cleanup

= 2.0.3 =
* Scheduled Notifications can now Trigger Alerts
* Fixed Stats Error on First Login
* Character Count on Manual Notifications

= 2.0.2 =
* Corrected Total Time On Site calculation
* Fixed additional bug with character encoding
* Adjusted styling on admin pages

= 2.0.1 =
* Fixed bug that prevented notifications from sending

= 2.0 =
* Automatically activate Safari Web Push with plugin
* Simplified interface
* Pulling All-Time stats for your Roost account into Wordpress Dashboard
* Added a Roost Push override on post pages to prevent notification
* Simplified install process - Plugin no longer creates it's own table
* Added support for featured images from posts (For new Roost app - Soon in Apple App Store and Google Play)
* Settings now all controlled from server
* Added Roost Bar shortcode
* Fixed character encoding issue on auto push notifications

= 1.1 =
* Added support for Safari Web Push (Desktop Push Notifications) on OS X Mavericks
* Prefixed functions to avoid conflicts
* Updated settings to be removed on uninstall instead of deactivation
* Included Roost JS on every page via wp_footer() - (Needed for Roost analytics such as page views and time on site)

= 1.0 =
* Initial Release

== Upgrade Notice ==
= 2.1.8 =
* Fixed URL causing W3C validation error -- removed invalid metrics in dashboard

= 2.1.7 =
* Major updates to roost.js (Performance updates and new APIs)
* Moved custom headline field to meta box on post screen

= 2.1.6 =
* Fixed bug with scheduled posts

= 2.1.5 =
* Added prompt control and option for custom text on notifications when publishing a post. Also, pre-check compatible with WordPress v4.0

= 2.1.4 =
* Bugfix - Fixed bug that prevented stats from displaying when WordPress admin accessed via HTTPS

= 2.1.3 =
* RoostJS now served from CDN (BAM! Blazing fast! You didn't see that one did you?)
* Removed mobile app setting

= 2.1.2 =
* Fixed a bug when sending manual notifications from the dashboard and with charts

= 2.1.1 =
* Fixed bug that prevented Roost admin screen from showing in PHP versions older than 5.3.0

= 2.1 =
* Charts and Graphs! bbPress support! Plugin redesign! Other Major Updates! Does it get any better?

= 2.0.5 =
* Now supports Auto Push when posting from the WordPress mobile app and by email via Jetpack by WordPress.com

= 2.0.4 =
* Limited Auto Push to 'post' types only. Fixes situation with other plugin's custom post types triggering notifications.

= 2.0.3 =
* Now with support for Scheduled Notifications! Bam! Oh, and some bugfixes and other cool stuff.

= 2.0.2 =
* Corrected Total Time On Site calculation
* Fixed additional bug with character encoding
* Adjusted styling on admin pages

= 2.0.1 =
* Fixed bug that prevented notifications from sending

= 2.0 =
* Bug fixes and analytics. Simplified interface and design update to match Wordpress 3.8 design.
