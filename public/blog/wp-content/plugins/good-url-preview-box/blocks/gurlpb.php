<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package good-url-preview-box
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */
function gurlpb_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = dirname( __FILE__ );

	$index_js = 'gurlpb/index.js';
	wp_register_script(
		'gurlpb-block-editor',
		plugins_url( $index_js, __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
		filemtime( "$dir/$index_js" )
	);

	$editor_css = 'gurlpb/editor.css';
	wp_register_style(
		'gurlpb-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'gurlpb/style.css';
	wp_register_style(
		'gurlpb-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'good-url-preview-box/gurlpb', array(
		'editor_script' => 'gurlpb-block-editor',
		'editor_style'  => 'gurlpb-block-editor',
		'style'         => 'gurlpb-block',
	) );
}
add_action( 'init', 'gurlpb_block_init' );
