/* globals module */
module.exports = {
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
	}
};
