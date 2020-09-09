const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { isProduction, isDevelopment } = require('webpack-mode');
const ReactRefreshWebpackPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const webpack = require('webpack');

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
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						plugins: [
							isDevelopment &&
								require.resolve('react-refresh/babel'),
						].filter(Boolean),
					},
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

			// less loader for ant design
			{
				test: /\.less$/,
				use: [
					{
						loader: 'style-loader',
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'less-loader',
						options: {
							lessOptions: {
								modifyVars: {
									'primary-color': '#787DFF',
									'link-color': '#787DFF',
									'border-radius-base': '3px',
								},
								javascriptEnabled: true,
							},
						},
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
		isProduction && new BundleAnalyzerPlugin(),

		isProduction && new CleanWebpackPlugin(),
		isDevelopment && new webpack.HotModuleReplacementPlugin(),
		isDevelopment && new ReactRefreshWebpackPlugin(),
	].filter(Boolean),

	resolve: {
		alias: {
			'@ant-design/icons/lib/dist$': path.resolve(
				process.cwd(),
				'assets/js/src/assets/icons',
				'index.js'
			),
		},
		extensions: ['.js', '.jsx'],
	},

	devServer: {
		port: 3000,
	},
};
module.exports = config;
