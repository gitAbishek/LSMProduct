const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { isProduction, isDevelopment } = require('webpack-mode');

const config = {
	entry: {
		app: path.resolve(process.cwd(), 'assets/js/src', 'index.js'),
	},
	output: {
		filename: '[name].js',
		path: path.resolve(process.cwd(), 'assets/js/build'),
	},

	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
				},
			},

			{
				test: /\.css$/,
				exclude: /node_modules/,
				use: [
					{
						loader: isProduction
							? MiniCSSExtractPlugin.loader
							: 'style-loader',
					},
					{
						loader: 'css-loader',
					},
				],
			},
		],
	},
	plugins: [
		isDevelopment &&
			new HtmlWebpackPlugin({
				template: './assets/js/src/index.html',
			}),

		// Extracts CSS in separate css file
		isProduction && new MiniCSSExtractPlugin({ filename: '[name].css' }),

		// Extracts dependencies on php file,which can be enqueued seperately in WordPress
		isProduction &&
			new DependencyExtractionWebpackPlugin({ injectPolyfill: true }),
	].filter(Boolean),
};

module.exports = config;
