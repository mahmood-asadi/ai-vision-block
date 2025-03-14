<?php
/**
 * Plugin Name: AI Vision Block
 * Description: A block that generates an image using Pollinations API and saves it to the media library.
 * Author: Mahmood Asadi
 * Author Email: asadi.pub@gmail.com
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ai-vision-block
 *
 * @package ai-vision-block
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load the block registration function.
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
