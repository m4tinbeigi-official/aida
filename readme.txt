=== Aida Chatbox ===
Contributors: m4tinbeigi-official
Donate link: https://ricksanchez.ir/
Tags: chatbox, chatbot, customer support, live chat, integration
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.0.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Aida Chatbox Integration for WordPress. Easily add the Aida chatbot to your site with a simple admin panel.

== Description ==

Aida Chatbox is a lightweight WordPress plugin that allows you to integrate the Aida AI-powered chatbot seamlessly into your website. With just a few clicks, you can add intelligent customer support to your site.

### Key Features:
* **Easy Setup**: Enter your Aida API key in the admin panel and start chatting.
* **Customizable Position**: Place the chatbox on the left or right side of the screen.
* **Initial State Control**: Set the chatbox to open or closed by default.
* **RTL Support**: Fully compatible with right-to-left languages like Persian (Farsi).
* **No Coding Required**: Simple settings page with documentation links.
* **Lightweight**: Minimal impact on site performance.

Get your free Aida API key from [Aida Dashboard](https://app.aidasales.ir/dashboard) and enhance your user engagement today!

For more details, visit the [Aida Website](https://aidasales.ir) or check the [Documentation](https://app.aidasales.ir/chatbox).

== Installation ==

1. Download the plugin ZIP file from the WordPress repository.
2. In your WordPress admin, go to **Plugins > Add New > Upload Plugin** and upload the ZIP.
3. Activate the plugin.
4. Go to **Aida Chatbox** in the admin menu.
5. Enter your Aida API key (from [Aida Dashboard > Channels > Website](https://app.aidasales.ir/dashboard)).
6. Adjust position and initial state as needed, then save changes.
7. The chatbox will appear on your site's frontend!

== Frequently Asked Questions ==

= Where do I get the Aida API key? =

Log in to your [Aida Dashboard](https://app.aidasales.ir/dashboard), go to **Channels > Website**, and generate/copy your API key.

= Does it support multiple languages? =

Yes! The plugin loads the Persian (Farsi) version by default, but check the [documentation](https://app.aidasales.ir/chatbox) for English or other languages.

= Can I customize the chatbox appearance? =

Basic positioning and state are customizable via the plugin settings. For advanced styling, refer to Aida's [docs](https://app.aidasales.ir/chatbox).

= What if the chatbox doesn't appear? =

Ensure your API key is valid and saved. Check browser console for errors. If issues persist, contact support via [Aida Website](https://aidasales.ir).


== Screenshots ==

1. **Admin Settings Page**: Configure API key, position, and initial state easily.
2. **Chatbox on Frontend**: Example of the chatbox in action on a live site.

(اگر اسکرین‌شات داری، فایل‌های PNG/JPG رو در فولدر `assets/screenshots/` بذار و لینک‌هاشون رو اینجا اضافه کن، مثل: 1. assets/screenshots/1-settings.png)

== Changelog ==

= 1.0.4 - October 26, 2025 =
* Removed deprecated load_plugin_textdomain() function for WordPress.org compatibility.
* Added proper output escaping (esc_html_e, esc_attr, etc.) for enhanced security.
* Included translators comments for internationalization placeholders.
* Used numbered placeholders (%1$s, %2$s) in translatable strings for better order.
* Enqueued chatbox script using wp_enqueue_script() and added data attributes via script_loader_tag filter.

= 1.0.3 - October 26, 2025 =
* Fixed plugin name length issue for WordPress.org compatibility.
* Updated text domain to 'aida-chatbox'.
* Improved RTL support in admin panel.

= 1.0.2 =
* Added initial state option (open/closed).
* Enhanced error notice for missing API key.

= 1.0.1 =
* Initial release with basic API key integration and position settings.

= 1.0.0 =
* First version with core functionality.