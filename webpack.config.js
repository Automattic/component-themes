const path = require('path');
const webpack = require( 'webpack' );

const settings = {
	module: {
		loaders: [
			{
				test: /\.json$/,
				loader: 'json',
			},
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
			},
			{
				test: require.resolve('react'),
				loader: 'expose-loader?React'
			},
			{
				test: require.resolve('./src/app'),
				loader: 'expose-loader?ComponentThemes!babel-loader?plugins=add-module-exports'
			},
			{
				test: require.resolve('./src/lib'),
				loader: 'expose-loader?ComponentThemes_lib!babel-loader?plugins=add-module-exports'
			}
		],
	},
	entry: {
		app: './src/app.js',
		'core-components': './src/themes/components/core-components.js',
	},
	output: {
		path: './build',
		filename: '[name].js'
	},
	node: {
		fs: 'empty'
	},
	externals: {
		'ComponentThemes': 'window.ComponentThemes',
		'ComponentThemes/lib': 'window.ComponentThemes_lib',
	},
	plugins: [ ]
};

if ( process.env.NODE_ENV === 'development' ) {
	settings.devtool = 'source-map';
} else if ( process.env.NODE_ENV === 'production' ) {
	settings.plugins.push( [
		new webpack.optimize.DedupePlugin(),
		new webpack.optimize.UglifyJsPlugin({ sourceMap: false }),
	] );
}

module.exports = settings;
