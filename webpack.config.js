/* globals module */
module.exports = {
	entry: './src/app.js',
	output: {
		path: './build',
		filename: 'app.js'
	},
	node: {
		fs: 'empty'
	}
};
