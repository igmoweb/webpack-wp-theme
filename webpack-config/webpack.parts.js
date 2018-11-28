const autoprefixer = require( 'autoprefixer' );
const ManifestPlugin = require( 'webpack-manifest-plugin' );
const chokidar = require( 'chokidar' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

const config = require( '../config.js' );

module.exports = {

	/**
	 * Webpack dev server config
	 */
	devServer: () => ({
		devServer: {

			// Watch for PHP files and reload the page
			before( app, server ) {
				const files = [
					'./**/*.php'
				];

				chokidar
					.watch( files, {
						alwaysStat: true,
						atomic: false,
						followSymlinks: false,
						ignoreInitial: true,
						ignorePermissionErrors: true,
						persistent: true,
						usePolling: true
					})
					.on( 'all', () => {
						server.sockWrite( server.sockets, 'content-changed' );
					});
			}
		}
	}),

	/**
	 * Sass loader config
	 */
	loadSass: ({ use = [], filename = '[name].[hash].css' } = {}) => {


		// Output extracted CSS to a file
		const plugin = new MiniCssExtractPlugin({
			filename
		});

		return {
			module: {
				rules: [
					{
						test: /\.scss$/,
						use
					}
				]
			},
			plugins: [ plugin ]
		};
	},

	/**
	 * Autoprfix loader config
	 */
	autoprefix: () => ({
		loader: 'postcss-loader',
		options: {
			browsers: config.COMPATIBILITY,
			plugins: () => [ autoprefixer() ]
		}
	}),

	/**
	 * JS Loader config. It transpiles JS from ES6
	 */
	loadJavaScript: () => ({
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /(node_modules|bower_components)/,
					use: [
						{
							loader: 'babel-loader',
							options: {
								presets: [ '@babel/preset-env', '@babel/preset-react' ]
							}
						}
					]
				}
			]
		}
	}),

	/**
	 * Webpack sourcemaps config
	 */
	generateSourceMaps: ({ type }) => ({
		devtool: type
	}),

	/**
	 * Generate a manifest JSON file in dist folder
	 */
	manifest: () => ({
		plugins: [ new ManifestPlugin() ]
	})
};
