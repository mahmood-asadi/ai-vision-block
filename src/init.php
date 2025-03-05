<?php
/**
 * Plugin Name: AI Vision Block
 * Description: A custom block for displaying AI-generated images.
 * Version: 1.0.0
 * Author: FLAREX
 * Author URI: https://flarex.agency/
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-vision-block
 *
 * @package ai-vision-block
 * @since 1.0.0
 * @link https://developer.wordpress.org/block-editor/
 */

/**
 * Registers the block and enqueues assets.
 */
function flarex_create_block_ai_image_block_block_init() {
	// Register the block using the metadata loaded from the `block.json` file.
	register_block_type( dirname( __DIR__ ) );
}
add_action( 'init', 'flarex_create_block_ai_image_block_block_init' );

/**
 * Enqueues the block's assets for the editor.
 */
function flarex_enqueue_block_editor_assets() {
	// Define the plugin directory path.
	$plugin_dir_path = plugin_dir_path( __FILE__ );

	$script_path = dirname( $plugin_dir_path ) . '/build/index.js';
	$style_path  = dirname( $plugin_dir_path ) . '/build/index.css';

	// Enqueue the block editor script.
	wp_enqueue_script(
		'ai-vision-block-editor',
		plugins_url( 'build/index.js', __DIR__ ),
		array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
		filemtime( $script_path )
	);
}
add_action( 'enqueue_block_editor_assets', 'flarex_enqueue_block_editor_assets' );

/**
 * Replaces the AI vision block with a core image block on post save.
 *
 * @param int $post_id The ID of the post being saved.
 */
function flarex_replace_ai_image_block_with_core_image_on_save( $post_id ) {
	// Avoid recursion and save only on main query.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	// Load the post content.
	$post = get_post( $post_id );
	if ( ! $post ) {
		return;
	}

	// Parse the blocks in the post content.
	$blocks   = parse_blocks( $post->post_content );
	$modified = false;

	// Iterate through the blocks to find the ai-image/block.
	foreach ( $blocks as &$block ) {
		if ( 'ai-image/block' === $block['blockName'] && isset( $block['attrs']['imageUrl'] ) ) {
			$image_url = $block['attrs']['imageUrl'];

			// Download the image and save it to the media library.
			$response = wp_remote_get( $image_url );
			if ( is_wp_error( $response ) ) {
				continue;
			}

			$image_data = wp_remote_retrieve_body( $response );
			$image_type = wp_remote_retrieve_header( $response, 'content-type' );

			if ( ! $image_data || ! $image_type ) {
				continue;
			}

			// Use WordPress file handling.
			// Sanitize the image name.
			$image_name = sanitize_file_name( uniqid( 'ai-image-', true ) . '.jpg' );
			$upload     = wp_upload_bits( $image_name, null, $image_data );
			if ( $upload['error'] ) {
				continue;
			}

			// Set up media attachment array.
			$attachment = array(
				'post_mime_type' => $image_type,
				'post_title'     => sanitize_file_name( basename( $image_url ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $upload['url'],
			);

			// Insert the attachment and generate metadata.
			$attachment_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
			wp_update_attachment_metadata( $attachment_id, $metadata );

			// Replace ai-image/block with core/image block.
			$new_image_url = wp_get_attachment_url( $attachment_id );
			$block         = array(
				'blockName'    => 'core/image',
				'attrs'        => array(
					'id'              => $attachment_id,
					'sizeSlug'        => 'full',
					'linkDestination' => 'none',
				),
				'innerContent' => array(
					'<figure class="wp-block-image size-full"><img src="' . esc_url( $new_image_url ) . '" alt="" class="wp-image-' . $attachment_id . '"/></figure>',
				),
			);

			$modified = true;
		}
	}

	// Update post content if modified.
	if ( $modified ) {
		$post->post_content = serialize_blocks( $blocks );
		remove_action( 'save_post', 'ai_image_save_to_media_library_on_save' );
		wp_update_post( $post );
		add_action( 'save_post', 'ai_image_save_to_media_library_on_save' );
	}
}
add_action( 'save_post', 'flarex_replace_ai_image_block_with_core_image_on_save' );
