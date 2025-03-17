=== AI Vision Block ===
Contributors: mahmoodasadi  
Tags: AI, image, block, Gutenberg, Pollinations  
Requires at least: 5.8  
Tested up to: 6.7
Requires PHP: 7.0  
Stable tag: 1.0.0  
License: GPL-2.0+  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  
Donate link: https://www.paypal.me/asadimahmood

Generate AI images using Pollinations API directly from the WordPress block editor and save them to the Media Library.

== Description ==

AI Vision Block is a custom Gutenberg block that allows you to generate images using the Pollinations API. Simply enter a prompt, and the AI will generate an image for you. Once the post is saved, the image is automatically stored in the WordPress Media Library.

**Features:**
- Simple and intuitive block editor UI.
- Uses Pollinations API for AI-generated images.
- Saves generated images to the WordPress Media Library.
- Lightweight and easy to use.

== Installation ==

1. Upload the `ai-vision-block` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to the block editor and add the "AI Vision Block" to your post or page.
4. Enter a prompt and click "Generate Image" to get an AI-generated image.

== External services ==

This plugin connects to an API to generate AI-based images. The service is used to process the user's prompt and return a generated image to be displayed in the WordPress post or page.

It sends the user's prompt, along with the width and height parameters for image resolution, every time the user generates an image.

This service is provided by "Pollinations AI":
- Terms of use & Privacy policy: https://pollinations.ai/terms


== Frequently Asked Questions ==

= How does the plugin generate images? =
The plugin uses the Pollinations API to generate images based on a given prompt.

= Where are the images stored? =
The generated images are saved to the WordPress Media Library when the post is saved.

= Do I need an API key? =
No, this plugin uses the Pollinations API without requiring an API key.

== Changelog ==

= 1.0.0 =
- Initial release with AI image generation and Media Library integration.

== Upgrade Notice ==

= 1.0.0 =
- First release of AI Vision Block. No upgrades needed.

== License ==

This plugin is released under the GPL-2.0+ license.
