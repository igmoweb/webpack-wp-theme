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

if ( ! function_exists( 'webpack_asset_path' ) ) :
	/**
	 * Return the assets manifest content
	 *
	 * @return array
	 */
	function webpack_asset_path( $filename, $manifest = 'manifest.json' ) {
		$dist_path     = get_stylesheet_directory() . '/dist/';
		$dist_url      = get_stylesheet_directory_uri() . '/dist/';
		$manifest_path = $dist_path . $manifest;

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
		} else {
			$manifest = [];
		}

		if ( array_key_exists( $filename, $manifest ) ) {
			return $dist_url . $manifest[ $filename ];
		}
		return $filename;
	}
endif;


if ( ! function_exists( 'webpack_theme_enqueue_scripts' ) ) :
	/**
	 * Enqueue the manifest scripts
	 */
	function webpack_theme_enqueue_scripts() {
		$suffix = '';
		if ( is_rtl() ) {
			$suffix = '.rtl';
		}

		wp_enqueue_style( 'webpack-stylesheet', webpack_asset_path( "assets/css/style${suffix}.css" ) );
		wp_enqueue_script( 'webpack-js', webpack_asset_path( 'assets/js/theme.js' ), [ 'jquery' ], null, true );
	}
endif;
add_action( 'wp_enqueue_scripts', 'webpack_theme_enqueue_scripts' );


if ( ! function_exists( 'webpack_theme_setup' ) ) :
	/**
	 * Setup the theme features
	 */
	function webpack_theme_setup() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'dist/assets/css/' . domestic_asset_path( 'editor-styles.css' ) );
	}
endif;
add_action( 'after_setup_theme', 'webpack_theme_setup' );
