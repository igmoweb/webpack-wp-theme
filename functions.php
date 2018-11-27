<?php

class Webpack_Theme {

	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
	}

	private function get_manifest() {
		$dist_folder = get_stylesheet_directory() . '/dist';
		return (array) json_decode( file_get_contents( $dist_folder . '/manifest.json' ) );
	}

	public function enqueue_scripts() {
		$assets_manifest = $this->get_manifest();

		$load_assets = [
			"css/style.css",
			"js/theme.js",
		];

		foreach ( $load_assets as $asset ) {
			if ( ! isset( $assets_manifest[ $asset ] ) ) {
				continue;
			}

			$ext = pathinfo( $asset, PATHINFO_EXTENSION );
			$asset_url = get_stylesheet_directory_uri() . '/dist/' . $assets_manifest[ $asset ];
			if ( $ext === 'css' ) {
				wp_enqueue_style( $asset, $asset_url );
			}
			elseif ( $ext === 'js' ) {
				wp_enqueue_script( $asset, $asset_url, [], null );
			}
		}
	}

	public function setup_theme() {
		$assets_manifest = $this->get_manifest();
		add_theme_support( 'editor-styles' );
		add_editor_style( $assets_manifest['css/editor-styles.css'] );
	}

}

$webpack_theme = new Webpack_Theme();
$webpack_theme->init();

