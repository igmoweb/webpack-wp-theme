<?php
/**
 * Webpack Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Webpack_Theme
 * @since 1.0.0
 */

/**
 * Return the assets manifest content
 *
 * @return array
 */
function webpack_theme_get_manifest() {
	$dist_folder = get_stylesheet_directory() . '/dist';
	return (array) json_decode( file_get_contents( $dist_folder . '/manifest.json' ) );
}

/**
 * Enqueue the manifest scripts
 */
function webpack_theme_enqueue_scripts() {
	$assets_manifest = $this->get_manifest();

	$load_assets = [
		'css/style.css',
		'js/theme.js',
	];

	foreach ( $load_assets as $asset ) {
		if ( ! isset( $assets_manifest[ $asset ] ) ) {
			continue;
		}

		$ext       = pathinfo( $asset, PATHINFO_EXTENSION );
		$asset_url = get_stylesheet_directory_uri() . '/dist/' . $assets_manifest[ $asset ];
		if ( 'css' === $ext ) {
			wp_enqueue_style( $asset, $asset_url );
		} elseif ( 'js' === $ext ) {
			wp_enqueue_script( $asset, $asset_url, [], null );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'webpack_theme_enqueue_scripts' );

/**
 * Setup the theme features
 */
function webpack_theme_setup() {
	$assets_manifest = $this->get_manifest();
	add_theme_support( 'editor-styles' );
	add_editor_style( $assets_manifest['css/editor-styles.css'] );
}
add_action( 'after_setup_theme', 'webpack_theme_setup' );
