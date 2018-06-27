=== Light Weight Cookie Popup ===

Contributors: tusharkapdi
Donate link: http://amplebrain.com/donate/
Tags: cookie, cookie accept, cookie popup, cookie notice, cookie law, popup, popup design, customizeable cookie popup, cookie days, simple popup box, country, display within countries, notice for GDPR, gdpr, eu cookie law regulations, cookie compliance, notification, notify, light weight cookie popup
Requires at least: 3.6
Tested up to: 4.9.6
Requires PHP: 5.2.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Light Weight Cookie Popup allows you to inform to users that your site uses cookies and to comply with the EU cookie law regulations.


== Description ==

**Light Weight Cookie Popup** where you can inform to users that your site uses cookies and to comply with the EU cookie law regulations.

= Options =

* Message (optional)
* Cookie Expiry (optional) : Default - 1 day
* Country (Display within selected countries) (optional) : Default - All Countries
* Position: Top/Bottom (optional) : Default - Bottom
* Popup Padding (optional) : Default - 15px
* Popup Background Color (optional) : Gray
* Popup Text Color (optional) : Default - Black
* Popup Font Size (optional) : Default - 13px
* Hide Close Button (optional) : Default - Show
* Close Button Text (optional) : Default - x
* Hide Accept Button (optional) : Default - Show
* Accept Button Text (optional) : Default - Ok
* Accept Button Background Color (optional) : Default - inherited from button tag
* Accept Button Text Color (optional) : Default - inherited from button tag
* Accept Button Class (optional)
* Hide Read More Link (optional) : Default - Show
* Read More Color (optional) : Default - inherited from theme style
* Read More Text (optional) : Default - Read more...
* Read More Link (optional) : Default - #
* Reamore Link Target (optional) : Default - _self
* Reamore Link class (optional)
* Multi language compatible
* .pot file included for translations

= Filters and Functions =

The code would be placed in your theme functions.php file or a custom plugin.


Would you like to do something if cookie accepted

`<?php
if ( function_exists('LWCP_cookie_accepted') && LWCP_cookie_accepted() ) {
    // Write your code here
}
?>`


This example for the override options by filtering 'lwcp_options_args' filter.

`<?php
function my_popup_options_args_filter( $options ) {
    // do something with $options
    return $options;
}
add_filter( 'lwcp_options_args', 'my_popup_options_args_filter', 10, 1 );
?>`


This example for the override output by filtering 'lwcp_cookie_output' filter.

`<?php
function my_popup_output_filter( $output, $options ) {
    // do something with $output and $options
    return $output;
}
add_filter( 'lwcp_cookie_output', 'my_popup_output_filter', 10, 2 );
?>`


= More Information =

* For help use [wordpress.org](http://wordpress.org/support/plugin/light-weight-cookie-popup/)
* Fork or contribute on [Github](https://github.com/tusharkapdi/light-weight-cookie-popup/)
* Visit [our website](http://amplebrain.com/light-weight-cookie-popup/) for more
* Follow me on [Twitter](http://twitter.com/tusharkapdi/)
* View my other [WordPress Plugins](https://profiles.wordpress.org/tusharkapdi/)

= Support =

Did you enjoy this plugin? Please [donate to support ongoing development](http://amplebrain.com/donate/). Your contribution would be greatly appreciated.


== Installation ==

1. Download and extract the zip archive
2. Upload 'light-weight-cookie-popup' folder to '/wp-content/plugins/'
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Manage popup settings through the 'Light Weight Cookie Popup' under the 'Settings' menu and configure the options as desired


== Frequently Asked Questions ==

= Detail about Third Party API for country detection =
Country is optional field and by default display popup on all countries and not access the Third Party API.
If you set countries then only it will access the Third Party API to get country name from IP Address.
The API called 'ipinfo.io' and Free usage of API is limited to 1,000 API requests per day.
Please find more inforamation about API here - [https://ipinfo.io/developers#rate-limits](https://ipinfo.io/developers#rate-limits).


== Screenshots ==

1. Popup options

== Changelog ==

= 1.0 =
* First release.


== Upgrade Notice ==

= 1.0 =
This is the initial release.
