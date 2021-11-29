const path = require( 'path' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const CleanWebpackPlugin = require( 'clean-webpack-plugin' );
const WebpackRTLPlugin = require( 'webpack-rtl-plugin' );
const wpPot = require( 'wp-pot' );

const inProduction = ( 'production' === process.env.NODE_ENV );
const mode = inProduction ? 'production' : 'development';

const config = {
	mode,
	entry: {
		'admin': [ './assets/src/js/admin/main.js', './assets/src/css/admin/main.scss' ],
		'frontend': [ './assets/src/js/frontend/main.js', './assets/src/css/frontend/main.scss' ],
	},
	output: {
		path: path.resolve( __dirname, './assets/dist/' ),
		filename: 'js/[name].js',
	},
	module: {
		rules: [

			// Use Babel to compile JS.
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
			},

			// Create RTL styles.
			{
				test: /\.css$/,
				use: [
					'style-loader',
					'css-loader',
				],
			},

			// SASS to CSS.
			{
				test: /\.scss$/,
				use: [
					MiniCSSExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: true,
						},
					} ],
			},
		],
	},

	// Plugins. Gotta have em'.
	plugins: [

		// Removes the "dist" folder before building.
		new CleanWebpackPlugin( [ 'assets/dist' ] ),

		new MiniCSSExtractPlugin( {
			filename: 'css/[name].css',
		} ),

	],
};

if ( inProduction ) {
	// Create RTL css.
	config.plugins.push( new WebpackRTLPlugin( {
		suffix: '-rtl',
		minify: true,
	} ) );

	// POT file.
	wpPot( {
		package: '{{title}}',
		domain: '{{name}}',
		destFile: 'languages/{{name}}.pot',
		relativeTo: './',
		src: [ './**/*.php', '!./vendor/**/*' ],
		bugReport: 'https://github.com/{ghusernane}/{{name}}/issues/new',
		team: 'Mehul Gohil <hello@mehulgohil.com>',
	} );
}

module.exports = config;
