=== Microblog Poster ===
Contributors: cybperic
Tags: microblogging, bookmarking, bookmarks, auto posts, auto publish, social signals, cross-post, cross post, auto submit, auto update, social networks, social media, twitter, facebook, linkedin, tumblr, delicious, diigo, plurk, friendfeed, blogger, automatic, automation, links, backlinks, auto updates status, social media auto publish, social network auto publish, publish to twitter, publish to facebook
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.4.4

Automatically publishes your new blog content to Social Networks. Auto-updates Twitter, Facebook, Linkedin, Tumblr, Diigo, Delicious..

== Description ==

**Auto updates your social media accounts/profiles** on each new blog post with a formatted message with backlink.
You only have to configure your social network accounts like Facebook, Twitter, LinkedIn, Tumblr. 
**Multiple Accounts per social site supported.**
Possibility to nicely format the update message per account, **shortcodes supported**.

**Logs are generated** on new blog post for each configured social network account.
Easily follow the automated sharing process from **MicroblogPoster**'s logs section and debug your configuration if needed.

**Custom Post Types** supported, additionally **Filter posts** to be published/cross posted to social networks based on categories. 
(General section settings)

**Currently supported social media sites**

* twitter.com - Auto tweet backlink of new blogpost.
* facebook.com - Auto publish to profile wall.
* plurk.com - Auto post new plurk.
* delicious.com - Auto submit bookmark of your blogpost to your account.
* friendfeed.com - Auto update your status.
* diigo.com - Auto submit bookmark of your new blogpost.
* linkedin.com - Auto publish to profile wall
* tumblr.com - Auto publish to your blog.
* blogger.com (blogspot.com) - Auto publish to your blog.
* instapaper.com - Auto submit bookmark of your new blogpost.

Please visit **MicroblogPoster**'s [website](http://efficientscripts.com/microblogposter "MicroblogPoster's website").


The idea behind **MicroblogPoster** is to promote your wordpress blog and reach more people through social networks like Facebook, Twitter, LinkedIn, Tumblr.. 
There's a general agreement in the SEO community that social signals strengthen your blog's page rank and authority.
**MicroblogPoster** is simply an intermediary between your blog and your own social network accounts. You'll never
see "posted by MicroblogPoster" in your updates, you'll see "posted by your own App name" or simply "by API".

**MicroblogPoster**'s Add-ons bring Additional Features: 
[Compare Versions](http://efficientscripts.com/microblogposteraddons "MicroblogPoster's Add-ons Page")


**MicroblogPoster** in few words:

- Auto publish to social media networks your new blog content
- Sends out social signals and auto share to social media networks
- Social signals and backlinks auto generator
- Cross post to facebook , twitter and more
- Auto publish to facebook , tumblr
- Auto share to twitter , facebook , linkedin
- Auto post to social media networks

== Screenshots ==

1. MicroblogPoster Options page, General Section.

2. MicroblogPoster Options page, Social Network Accounts.

3. MicroblogPoster Options page, Logs Section.

== Changelog ==

= 1.4.4 (28-10-2014) =
- Added support for new lines in message format field.
- Fixed bug about tumblr.com accounts when edited.
- Added more url shorteners in Enterprise version.

= 1.4.3 (30-09-2014) =
- Adapted the free version of the plugin to work together with the new Enterprise add-on. Additional features available.

= 1.4.2 (19-08-2014) =
- Added support for goo.gl url shortener.
- Improved auto posting for scheduled items.

= 1.4.1 (15-07-2014) =
- Added support for instapaper auto publish.

= 1.4.0 (17-06-2014) =
- Added support for blogger (blogspot) auto publish.

= 1.3.9 (11-05-2014) =
- Added support for custom post types.

= 1.3.8 (16-03-2014) =
- Enabled twitter authorization process interactively, which allows to use multiple twitter accounts with a single twitter App.

= 1.3.7 (16-02-2014) =
- Added possibility to adjust length of : {EXCERPT}, {CONTENT_FIRST_WORDS}, {TITLE}.
- Improved auto publishing to linkedin.

= 1.3.6 (26-01-2014) =
- Added following shortcodes : {EXCERPT}, {MANUAL_EXCERPT}, {AUTHOR}, {CONTENT_FIRST_WORDS}.
- Improved auto posting to delicious and diigo.
- Improved auto posting to friendfeed.
- In logs section, added 'Empty Logs' button.

= 1.3.5 (06-11-2013) =
- Added support for tumblr.
- Improved design of plugin's settings page.

= 1.3.4 (27-09-2013) =
- Fixed internal error 500 that was occurring for some PHP/web server configurations, related to the use of method_exists function.
- Added 'Settings' link on plugins page.

= 1.3.3 (15-09-2013) =
- Adapted the free version of the plugin to work together with the new pro add-on. Additional features available with the pro add-on.

= 1.3.2 (31-07-2013) =
- Fixed critical error about a PHP warning produced by variable not being an array. Later that produces header already sent error.

= 1.3.1 (20-07-2013) =
- Added currently recommended way of authentication with bit.ly (oauth).
- MicroblogPoster's control checkbox moved from right side to center.
- Dropped support for identi.ca because of the complete change of their API.
- Added possibility to choose plurk qualifier.
- In general section exclude posts from checked categories cross-posting automatically.

= 1.3.0 (01-07-2013) =
- linkedin.com is now supported.
- facebook posting improvements (text only or share a link).
- Added possibility to post featured image (facebook and linkedin cross posting).
- Logging failed authorizations to help debugging.

= 1.2.7 (28-06-2013) =
- linkedin.com is now supported.
- facebook posting improvements (text only or share a link).
- Added possibility to post featured image (facebook and linkedin cross posting).
- Logging failed authorizations to help debugging.

= 1.2.61 (12-06-2013) =
- Urgent twitter api fix

= 1.2.6 (02-06-2013) =
- diigo.com is now supported
- Added possibility to cross-post on new page creation.
- General options layout improvements + added options for page cross posting.

= 1.2.5 (12-05-2013) =
- Logs are now generated for each new blog post per social account.
- Tabified the plugin settings page, added logs section.
- Added option for default post update behavior.
- Facebook account authorization process improved.
- Fixed several small bugs.

= 1.2.4 (28-04-2013) =
- Possibility to format the message that's posted, shortcodes support.
- For HTTP Auth sites, passwords are stored encrypted in db.
- New option for delicious site, choose if tags included.
- Bug fix, double escaping.

= 1.2.3 (16-04-2013) =
- facebook.com is now supported.
- 'default per post behavior' option added.
- added images for each supported site.

= 1.2.2 =
- Multiple Accounts per site supported.
- More user friendly plugin settings interface.

= 1.2.1 =
- Added microblogging site friendfeed.com

= 1.2 =
- Added bookmarking site delicious.com

= 1.1 =
- Added microblogging site identi.ca

= 1.0 =
- First version of Plugin Released.

== Installation ==

* Upload the contents of the microblogposter folder to your /wp-content/plugins/ folder.
* Activate the Plugin through the 'Plugins' menu in WordPress
* Settings->MicroblogPoster, configure your social network accounts.
* The plugin is ready, it will automatically cross posts to social networks whenever you publish a new blog post.


**twitter.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/twitterhelp "Twitter help with screenshots.")

Your Twitter username and password won't suffice in order to post automatic updates, Twitter API requires some more steps described below.
No worries, it's rather a simple procedure.


    1. In order to auto post updates through the Twitter API you'll need
    to create your own Twitter App here: https://apps.twitter.com/
    
    2. Once you have created your Twitter App you have to change its Access Level
    to be Read and Write. 
    Browse to the settings tab and click on 'Modify App Permissions'.
    Check the Access Level to be Read and Write. Save your Settings.

    3. Once this is done return to the API Keys tab, at the bottom you 
    should have a button 'Create My Access token', please do it.

    4. Now, on the API Keys tab you have all what you need, 
    i.e. API (Consumer) key / secret, Access token and Access token secret.

    5. If you don't see immediately the Access Token at the bottom, 
    please refresh the API Keys tab page.


**plurk.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/plurkhelp "Plurk help with screenshots.")

It's most likely the same as for twitter, you'll need some more effort in order to post updates through Plurk Api.


    1. Please browse to this url http://www.plurk.com/PlurkApp/ 
    and click on 'Create a New Plurk App'.
    For App Type choose 'Third-party web site integration'. 
    For App Website you can put http://localhost

    2. Once you are back on 'My Plurk Apps' page, click the edit button 
    and copy your *App Key and App Secret*.

    3. Not finished yet, you need the second pair of credentials. 
    On 'My Plurk Apps' page, this time click on 'Test Console' button.

    4. First, click on 'Get Request Token', then some processing is done. 
    After that, click on 'Open Authorization Url', you'll be redirected 
    to a new page and you will have to grant the permission in order to get
    your verification code.

    5. Finally, return to the previous page and generate your 
    *Token Key and Token Secret* by clicking on
    'Get Access Token' and by providing the verification code.

    6. Now, you can copy your Token Key and Token Secret. 
    Coupled with the App key and App secret you've got previously 
    you can configure your plurk account in the Social Accounts Section.



**facebook.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/facebookhelp "Facebook help with screenshots.")

Please note that only real personal Facebook accounts have permissions to create an App.
Business accounts can't manage Facebook Apps.

    1. Please browse to this url https://developers.facebook.com/apps 
    and click on 'Apps' -> 'Create new App'.
    Note: If you can not click on 'Create new App', you'll need first 
    to 'Register as a Developer'.
    Then the creation of New App will be available.

    2. Fill in your 'App Name' and then, click continue.

    3. Enter the required Security Check.

    4. Once on the basic settings of your new App, 
    for the field 'App Domains' enter your Blog domain name 
    (example: mydomain.com)
    Then click 'Add Platform' and choose 'Website'.
    
    5. For the field 'Site url' enter your Blog Url.
    (example: http://mydomain.com)
    Save changes.
    
    6. Make your App Live by browsing to 'Status & Review'.

    7. Copy your 'App ID' and 'App Secret' and configure your Facebook account
    in MicroblogPoster's Social Accounts Section.

    8. Follow the link provided by MicroblogPoster to authorize your App 
    posting on your behalf.


**friendfeed.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/friendfeedhelp "FriendFeed help with screenshots.")


Instead of the password, the Friendfeed API requires the Remote Key to let you post with it.

    1. You can find your Remote Key associated with your account at this Url:
    https://friendfeed.com/account/api (You must be logged in).


**diigo.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/diigohelp "Diigo help with screenshots.")


In addition to your username and password you will need to create your own Diigo App and generate an API Key.

    1. Please browse to this Url: https://www.diigo.com/api_keys/new/
    and generate your Diigo API Key. (You need to be logged in)


**linkedin.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/linkedinhelp "Linkedin help with screenshots.")


    1. Please browse to this Url https://www.linkedin.com/secure/developer
    and click on 'Add New Application'.

    2. Fill in the required informations.
    For 'Live Status', select Live.
    Leave everything else by Default.
    Click the button 'Add Application'.

    3. Copy 'Api Key' and 'Secret Key', and click 'Done'.

    4. Configure your Linkedin account in the Social Accounts Section.

    5. Follow the link provided by MicroblogPoster to authorize your App 
    posting on your behalf.

**tumblr.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/tumblrhelp "Tumblr help with screenshots.")

Basically your tumblr username and password won't suffice in order to post automatic updates, tumblr Api requires some more steps described below.
No worries, it's rather a simple procedure.


    1. In order to post updates through the tumblr Api you'll need first
    to create your own tumblr App here: http://www.tumblr.com/oauth/apps
    
    2. Once, you have created your tumblr App copy your 'OAuth Consumer key'.

    3. Also click on 'Show Secret key' and copy your 'Secret key'.

    4. This is it, you can now configure your Account in the Social Accounts section.

**blogger.com accounts** [Help with screenshots](http://efficientscripts.com/help/microblogposter/bloggerhelp "Blogger help with screenshots.")

Basically first you'll need to request the activation from Google of the Blogger API. It takes about 5 working days.
Once it's activated you'll need to create your 'Client ID' (aka App) which will auto post on your behalf.


    1. Please browse to this url: https://console.developers.google.com/
    Select 'API Project', then on left side select 'APIs & auth'. The 'APIs' tab is selected by default.
    
    2. Scroll down and locate 'Blogger API v3'.
    Click the 'OFF' button in order to request the activation. 

    3. After few days you'll receive an email from Google asking to complete the activation.
    Please follow the steps provided.

    4. Please browse to this url again: https://console.developers.google.com/
    Select 'API Project', then on left side select 'APIs & auth' -> 'Credentials'

    5. Click on 'Create new Client ID'

    6. For 'Application type', select 'Web application'.
    For 'Authorized Javascript Origins', enter your blog url (ex: http://www.yourblog.com)
    For 'Authorized Redirect Uri', enter the Url when you're on the settings of MicroblogPoster.
    It is something like http://www.yourblog.com/wp-admin/options-general.php?page=microblogposter.php
    Click 'Create Client ID'.

    7. Copy the Client ID and Client secret and configure your account under MicroblogPoster.

    8. MicroblogPoster will provide you a link in order to authorize the App posting on your behalf.


== Upgrade Notice ==

Deactivate/Activate MicroblogPoster plugin.

== Frequently Asked Questions ==

= My blog is hosted on shared hosting, can I use MicroblogPoster? =

Warning about inherent php script execution time limitation that some Hosting Providers apply on shared accounts (max_execution_time PHP setting). 
Since *MicroblogPoster* needs time to update all your social accounts when publishing a new blog post, this limit might be reached and script execution stopped.
In order to avoid it, please limit the number of social accounts based on your environment script execution time limit.

= The PHP cURL extension is required? =

Yes, otherwise the plugin simply won't function at all.

