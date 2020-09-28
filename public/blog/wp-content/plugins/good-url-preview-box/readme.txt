=== Plugin Name ===
Contributors: mraliende
Donate link: https://guteurls.de/donate.php
Tags: link, links, URL, URLs, preview, preview image, guteurls, gute-urls, facebook, internet-link, post, posting, like Facebook, exacly like facebook, find url, find link, search link, search url, detect url, internet address
Requires at least: 4.9.0
Tested up to: 5.3.2
Stable tag: 5.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds link preview box to your post. You just copied & pasted an URL into your posting. :-)

== Description ==

The Plugin adds an link preview box to your post.
The link preview box will be displayed like in Facebook! :-)

This plugin can be used for free for maximal 10 URL Preview Boxes.

Perhaps you are familiar with Facebook.
When you write a post in Facebook and you paste an URL into your text,
Facebook detects that URL and adds automatically information about that URL under your post.

This plugin does it not automatically, you have to use the extra button in the visual editor ("URL BOX").
Or you just write \[urlpreviewbox url="\https://...."\] into your text.
This plugin loads information about that URL from https://guteurls.de/ server.

1. Title of that page
2. Description of that page
3. An URL of an image

And the plugin displays it below your post.

Facebook supports only one preview box per post.
This plugin supports many preview boxes within one post, if you want.

Do you want to see it live on production servers?

Look here (copy and paste the URLs into your browser):

Example web pages, who use it:

https://alien.de/news/

https://www.ufo-und-alienforum.de/index.php/Thread/30805-Verlassene-Orte-in-Baden-Württemberg/

https://hypnose54321.de/frankfurt/_news.php

:


HOW TO USE IT VIDEO:

http://www.youtube.com/watch?v=4_wIzQbPg84
[youtube http://www.youtube.com/watch?v=4_wIzQbPg84]


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Install the plugin through the WordPress plugins screen directly.
2. Or (optional) unzip our archive and upload tthe plugin files to the `/wp-content/plugins/plugin-name` directory.
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Use the Settings->"URL Preview" screen to configure the plugin
5. If you are a company, then please register at http://guteurls.de and paste your registration-code into the plugin setting page Settings->"URL Preview"

== Upgrade Notice ==

No upgrades yet.

== Frequently Asked Questions ==

= Why do I NOT see a preview box, when I write a post =

This is correct. If you want to see the preview box, then you have to exit the admin area.
What you have to do is, save/update your post and then click on "View post" and then you see it.

= Why does the preview box sometimes does not show up? =

Sometimes there are not enough data to fill the preview box.
We do not support pdf-links, mp3-links, m4-links, ...
Only html pages are supported.

= Why does the preview box sometimes shows up only after reload? =

It needs time to collect data from other webpages
and sometimes we have to make a screenshot from the page.
That needs some seconds.
When you click reload, then the data are available/collected/cached.


== Internationalization (i18n) ==
Currently we have the plugin configured so it can be easily translated and the following languages supported:

* de_DE - German in Germany
* en_US - English in the U.S.
* zh_CN - China
* es_ES - Spanish in Spain
* ru_RU - Russian in the Russian Federation

If your language is not listed above, feel free to create a translation. Here are the basic steps:

1. Copy "gurlpb-en.pot" to "gurlpb-LANG_COUNTRY.pot" - fill in LANG and COUNTRY with whatever you use for WPLANG in wp-config.php
2. Grab a transalation editor. [POedit](http://www.poedit.net/) works for us
3. Translate each line

== Screenshots ==

1. First screen. Register to use full functionality.
2. Writing an post and adding an URL for the spacex.com career.

== Upgrade Notice ==

= 1.20 =
Hot Bugfix for Gutenberg 5.3 - is_gutenberg_page() is not working in gutenberg anymore!?!?

= 1.19 =
Bugfix for user with licence-key: More then 10 preview box per post are allowed yet.

= 1.18 =
Bugfix for Wordpress 4.9.9 and smaller

= 1.17 =
Two big modifications: 
- Support of the new Wordpress Editor. Please select embed block "Url Preview Box".
- In case you do not have a licence key, you are limited to 10 URL Preview Boxes.

= 1.12 =
Added in admin area: 
- Set maximal number of lines for a url preview box
- Change the clickable text link in an url preview bo

= 1.11.3 =
Registration page added.

= 1.10.1 =
Better reset/reload of cached URL data

= 1.9.1 =
Added: Reset data for one single preview box (force reload image and description).
I love to hear your suggestions, to make it even better.

= 1.9 =
Statistics added. How many times are the preview boxed displayed. Many views in one user session is counted as one view.

= 1.7 =
Nothing big. No new feature. no new functions. Only a bugfix.
Bugfix: Text "Guteurls loading..." did not disappear. Now a JS will hide it, after loading page.

= 1.6 =
* Bugfix: Article title link to external URL
* New feature: Admin settings: hide preview image

= 1.5 =
* Excerpt: visible box in excerpt

= 1.4 =
Nothing new for users, just bugfixes

= 1.3 =
Nothing new for users, just bugfixes

= 1.2 =
In the settings for this plugin are now some new properties.
And in the default text editor is a new button with the label "URL box".

= 1.1 =
Minor bugfix

= 1.0 =
First public release.


== Changelog ==

= 1.20 =
Hot Bugfix for Gutenberg 5.3 - is_gutenberg_page() is not working in gutenberg anymore!?!?

= 1.19 =
Bugfix for user with licence-key: More then 10 preview box per post are allowed yet.

= 1.18 =
Bugfix for Wordpress 4.9.9 and smaller

= 1.17 =
Support of the new Wordpress Editor Gutenberg.
Plugin limit you to 10 Boxes, if you do not have purchased a licence key.

= 1.12 =
Added in admin area: 
- Set maximal number of lines for a url preview box
- Change the clickable text link in an url preview box

= 1.11.3 =
Registration page added.

= 1.10.1 =
Better reset/reload of cached URL data

= 1.9.1 =
Added: Reset data for one single preview box (force reload image and description).

= 1.9 =
* Statistics added. How many times are the preview boxed displayed. Many views in one user session is counted as one view.

= 1.8 =
Not used this version number.

= 1.7 =
* Bugfix: Text: "Guteurls loading..." did not disapear. Now a js will hide it, after loading page.

= 1.6 =
* Bugfix: Article title link to external URL
* New feature: Admin settings: hide preview image

= 1.5 =
* Excerpt: visible box in excerpt

= 1.4 =
* bugfix: visible [urlpreviewbox url=....]

= 1.2 =
* New feature: Now you can define manually in the text editor, which URL should have an URL preview box.

= 1.1 =
* Minor bugfix

= 1.0 =
* First public release.


`<?php code(); // goes in backticks ?>`
