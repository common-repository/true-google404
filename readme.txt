=== True Google 404===
Contributors: technofreak
Plugin Name: True Google 404
Plugin URI: http://thetechnofreak.com/technofreak/wordpress-plugin-true-google-404/
Tags: 404, search, google, error, page not found, missing, seo, 404 log, report
Requires at least: 2.7
Tested up to: 3.4.2
Stable tag: 1.4.1

Transform a dumb 404 to a list of related links with short descriptions.
Keep your readers with you and do not irritate them with dumb 404s


== Description ==
Using the trueGoogleSearch API by http://theTechnofreak.com , this plugin will intercept your standard 404 page and return a list of urls that may help your user find the content they are looking for.
You can limit the search to just the content on your site, set a default prefix to the search query (for example in case you want to limit the search to some keywords). This provides your users with quality recomendations.
Thus keep your readers with your site with this plugin and do not irritate them with dumb 404s.

This plugin should work out-of-the-box with most themes using the included 404 template.
You can also define your own to fine tune the look and feel to match your theme.


== Installation ==
* Download the plugin with the link at right and upload the plugin to your blog **OR**
* simply go to **plugins->add new->search** on *your* wordpress website and search for "*true google 404*". Click on "*install now*"
1. Activate the plugin.
2. Go to true Google 404 settings and tweak the settings as you like.
3. If you want to use the plugin while using your theme's 404.php, add the line:

`<?php if(function_exists('trueGoogle404'))echo trueGoogle404();?>`

4. You're golden. Congrats! No more flop 404's

Wherever you want the results to show.

== Frequently Asked Questions ==

= Don't you have a live example of this plugin? =
No, we have it. Try it out by typing a non existant url on my website.
For example go to http://thetechnofreak.com/my-wordpress-plugin
or try any other path instead of [my-wordpress-plugin] to see the plugin in action.
= I am no more getting any suggestions on a 404 page. Why? =
Your server's IP address probably got blocked by google for excessive queries. Contact the plugin author to get additional functionality of using proxies. 

== Screenshots ==

1. True Google 404 Settings page.
2. True Google 404 at work on http://thetechnofreak.com (try it out by typing a non existant url, eg http://thetechnofreak.com/my-wordpress-plugin )

== Upgrade Notice ==

= 1.0.2 =
Minor change. Upgrade not necessary.

== Changelog ==

= 1.0.1 =
* Initial public release
