=== More Sorting Options for WooCommerce ===
Contributors: algoritmika, anbinder
Tags: woocommerce, sorting
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 3.1.3
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add new custom, rearrange, remove or rename WooCommerce sorting options.

== Description ==

Plugin extends WooCommerce by adding new **custom sorting** options:

* Title: A to Z
* Title: Z to A
* Slug: A to Z
* Slug: Z to A
* SKU: Ascending
* SKU: Descending
* Stock Quantity: Ascending
* Stock Quantity: Descending
* Number of Comments: Ascending
* Number of Comments: Descending
* Total Sales: Ascending
* Total Sales: Descending
* Product ID: Ascending
* Product ID: Descending
* Last Modified Date: Oldest to Newest
* Last Modified Date: Newest to Oldest
* Date: Ascending
* Author: Ascending
* Author: Descending
* No sorting
* Random sorting

Additionally you can add your own **custom meta** sorting options.

With this plugin you can also **rearrange order** of sorting options (including WooCommerce default) on frontend.

Premium version also allows to **rename or completely remove** default WooCommerce sorting options.

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Drop us a line at http://www.algoritmika.com.

= More =
* Visit [More Sorting Options for WooCommerce](https://wpcodefactory.com/item/more-sorting-options-for-woocommerce-wordpress-plugin/) plugin page.

== Installation ==

1. Upload the entire 'woocommerce-more-sorting' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. More sorting options will be automatically added to WooCommerce plugin.

== Frequently Asked Questions ==

= Can I change text for added sorting options? =

Yes, just go to "WooCommerce > Settings > More Sorting".

= Will added sorting options work as default options? =

Yes, You can set added sorting options work as default - just go to "WooCommerce > Settings > Products > Display > Default Product Sorting".

== Screenshots ==

1. Admin options.

== Changelog ==

= 3.1.3 - 23/01/2018 =
* Add sorting by date - descending
* Add composer to handle dependencies

= 3.1.2 - 21/10/2017 =
* Dev - WooCommerce v3.2.0 compatibility - Admin settings `select` type fixed.
* Dev - "Remove All Sorting" now only removes sorting from frontend and leaves it enabled in backend (so e.g. default product sorting can be set to some custom sorting).
* Dev - Savings setting array as main class property.
* Dev - Code refactoring.

= 3.1.1 - 23/07/2017 =
* Dev - WooCommerce v3.0.0 compatibility - `woocommerce_clean()` replaced with `wc_clean()`.
* Dev - Advanced - Restore default WooCommerce Sorting - `remove_action` added to `avada` option.
* Dev - Plugin header ("Text Domain") updated.
* Dev - Link updated from http://coder.fm to https://wpcodefactory.com.

= 3.1.0 - 08/03/2017 =
* Dev - "Custom Meta Sorting" section added.
* Dev - Custom Sorting - "No sorting" option added.
* Dev - Custom Sorting - "Random sorting" option added.
* Dev - Custom Sorting - "Sort by number of comments" options added.
* Dev - Custom Sorting - "Sort by slug" options added.
* Dev - Custom Sorting - "Sort by total sales" options added.
* Dev - Custom Sorting - "Sort by product ID" options added.
* Dev - Custom Sorting - "Sort by date (ascending)" option added.
* Dev - Custom Sorting - "Sort by author" options added.
* Dev - Custom Sorting - "Sort by last modified date" options added.
* Dev - Advanced - "Restore default WooCommerce Sorting" option added.
* Dev - Admin settings divided into separate sections. Dashboard added to General settings section.
* Dev - `ID` added as second `orderby` param.
* Dev - "Reset settings" checkbox added.
* Dev - Code refactoring: separate `order` param added (e.g. `sku_asc` replaced with `sku-asc`); `title` removed as it already exists in WooCommerce default function.

= 3.0.2 - 19/12/2016 =
* Fix - Multisite WooCommerce check fixed.

= 3.0.1 - 15/12/2016 =
* Fix - `handle_deprecated_options()` fixed. This produced notice on plugin activation.

= 3.0.0 - 13/12/2016 =
* Fix - `load_plugin_textdomain()` moved from `init` hook to constructor.
* Dev - Remove All Sorting - Empty `loop/orderby.php` template added to ensure maximum compatibility;
* Dev - Remove All Sorting - Storefront theme compatibility added.
* Dev - Remove All Sorting - `init` hook replaced with `wp_loaded` for `remove_sorting()`.
* Dev - "Rearrange Sorting" section added.
* Dev - "Default Sorting Options" section added.
* Dev - Code refactoring. "Custom Sorting" - "Enable Section" checkbox added. Functions renamed etc.
* Tweak - Plugin renamed.

= 2.1.0 - 08/10/2016 =
* Dev - Version variable added.
* Dev - Multisite support added.
* Fix - Coder.fm link fixed.
* Tweak - Plugin renamed.
* Tweak - Author added.
* Tweak - Readme.txt header updated.
* Tweak - Language (POT) file added.

= 2.0.1 - 27/08/2015 =
* Dev - Remove All Sorting - Blaszok theme compatibility added.

= 2.0.0 - 29/07/2015 =
* Dev - Option to treat SKUs as numbers or texts when sorting, added.
* Dev - Sorting by stock quantity - added.
* Dev - Major code refactoring. Settings are moved to "WooCommerce > Settings > More Sorting Pro".

= 1.0.2 =
* 'Remove any sorting option' option added
* Sort by SKU option added
* Default sorting bug fixed

= 1.0.1 =
* 'Remove all sorting' option added

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 3.1.3 =
* Add sorting by date - descending