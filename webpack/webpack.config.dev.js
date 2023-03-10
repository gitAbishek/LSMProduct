const ReactRefreshWebpackPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const DotEnv = require('dotenv').config();
const WebPackDotEnv = require('dotenv-webpack');
const baseConfig = require('./config.base');
const ForkTsCheckerPlugin = require('fork-ts-checker-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');
const WebpackBar = require('webpackbar');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

module.exports = () => ({
	entry: baseConfig.paths.entry,

	output: {
		filename: '[name].js',
		path: baseConfig.paths.output,
		publicPath: 'http://localhost:3000/dist',
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
					limit: 150000,
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
				],
			},

			{
				test: /\.scss$/i,
				use: [
					{
						loader: 'style-loader',
					},
					{
						loader: 'css-loader',
					},
					{
						loader: 'sass-loader',
					},
				],
			},
		],
	},

	plugins: [
		new ReactRefreshWebpackPlugin({
			overlay: false,
		}),
		new WebPackDotEnv(),
		new ForkTsCheckerPlugin({
			async: true,
		}),
		new DependencyExtractionWebpackPlugin(),
		new EslintPlugin({
			extensions: ['js', 'jsx', 'ts', 'tsx'],
		}),
		new WebpackBar(),
	].filter(Boolean),

	resolve: baseConfig.resolver,
	devServer: {
		headers: { 'Access-Control-Allow-Origin': '*' },
		allowedHosts: [DotEnv.parsed.WORDPRESS_URL.replace('http://', '')],
		host: 'localhost',
		port: 3000,
	},
	devtool: 'cheap-module-source-map',
});
