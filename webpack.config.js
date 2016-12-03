/* globals module */
module.exports = {
	module: {
		loaders: [
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
