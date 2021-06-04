const HtmlWebpackPlugin = require('html-webpack-plugin');
const ReactRefreshWebpackPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const ErrorOverlayPlugin = require('error-overlay-webpack-plugin');
const Dotenv = require('dotenv-webpack');
const baseConfig = require('./config.base');
const WebpackBar = require('webpackbar');
const ForkTsCheckerPlugin = require('fork-ts-checker-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');

const config = {
	entry: baseConfig.paths.entry,

	output: {
		filename: '[name].js',
		path: baseConfig.paths.output,
	},

	module: {
		rules: [
			{
				test: /\.(js|jsx|svg|ts|tsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						plugins: [require.resolve('react-refresh/babel')].filter(Boolean),
					},
				},
			},
			{
				test: [/\.bmp$/, /\.gif$/, /\.jpe?g$/, /\.png$/],
				loader: require.resolve('url-loader'),
				options: {
					limit: 10000,
					name: 'static/media/[name].[hash:8].[ext]',
				},
			},

			{
				test: /\.css$/i,
				use: [
					{
						loader: 'style-loader',
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'postcss-loader',
					},
				],
			},
		],
	},

	plugins: [
		new HtmlWebpackPlugin({
			filename: 'index.html',
			template: './assets/js/back-end/index.html',
		}),

		new HtmlWebpackPlugin({
			filename: 'interactive.html',
			template: './assets/js/back-end/index.html',
		}),

		new ReactRefreshWebpackPlugin({
			overlay: false,
		}),
		new ErrorOverlayPlugin(),
		new Dotenv(),
		new WebpackBar(),
		new ForkTsCheckerPlugin({
			async: false,
		}),
		new EslintPlugin({
			extensions: ['js', 'jsx', 'ts', 'tsx'],
		}),
	].filter(Boolean),

	resolve: baseConfig.resolver,

	devServer: baseConfig.devServer,

	devtool: 'cheap-module-source-map',
};
module.exports = config;
