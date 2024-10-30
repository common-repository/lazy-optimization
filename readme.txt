=== Lazy Optimization ===
Contributors: yasir129
Tags: lazyload background images, background images, lazyload, Lazy Loading, image lazy load, lazyload images, lazyloading,  performance, speed, image, autoptimize
Requires at least: 4.0
Tested up to: 5.4
Requires PHP: 5.3
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lazy Optimization speeds up your website by lazy loading background images that are in the external CSS files.

== Description ==

Lazy Optimization plugin is the first ever WordPress plugin to lazy load background images that are in external CSS files.
Lazy Optimization plugin works on top of Autoptimize plugin and is used to lazy load background images that are present in Autoptimize external CSS files.
Lazy Optimization plugin replace all the background images that are in Autoptimze external CSS files with a dummy image and when the element with the background image comes in viewport it's original background images gets loaded.

> <strong>Dependices</strong> [Autoptimize](https://wordpress.org/plugins/autoptimize/).

[youtube https://www.youtube.com/watch?v=5Enr7OHNtIQ]

== Installation ==

Search for "Lazy Optimization" under "Plugins" → "Add New" in your WordPress dashboard to install the plugin.

Or install it manually:

1. Download the [plugin zip file](https://downloads.wordpress.org/plugin/lazy-optimization.zip).
2. Go to *Plugins* → *Add New* in your WordPress admin.
3. Click on the *Upload Plugin* button.
4. Select the file you downloaded.
5. Click *Install Plugin*.
6. Activate.

== Frequently Asked Questions ==

= Can I exclude background images from being lazy loaded? =

Yes, you can. Go to Settings › lazy optimization, there you can see the option to exclude background images (by image name) from being lazy loaded.

= Can I replace dummy background image? =

Yes, you can. Go to Settings › lazy optimization, there you can see the option to replace the dummy background image.

= Some background image are not being loazy laoded? =

As Lazy Optimization plugin works on top of Autoptimize plugin so it will lazy loads background images that are in Autoptimize external CSS files. Any background image that is not in the Autoptimize external CSS files will not get lazy loaded.

== Changelog ==

= 1.0.3 =
* Some Changes to JS Code and added index.php where necessary

= 1.0.2 =
* Moved CSS and JS folder to wp-content folder so that upon updating the plugin those folders don't get overwritten

= 1.0.1 =
* readme.txt updated

= 1.0.0 =
* Initial release