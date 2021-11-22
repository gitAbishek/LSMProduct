const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const ForkTsCheckerPlugin = require('fork-ts-checker-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');
const WebpackBar = require('webpackbar');
const Dotenv = require('dotenv-webpack');
const path = require('path');
const BundleAnalyzerPlugin =
	require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const PACKAGE = require('../package.json');
const version = PACKAGE.version;

module.exports = (env) => ({
	entry: {
		blocks: path.resolve(process.cwd(), 'assets/js/blocks', 'index.tsx'),
	},
	output: {
		filename: `[name].${version}.js`,
		path: path.resolve(process.cwd(), 'assets/js/build'),
	},
	module: {
		rules: [
			{
				test: /\.(js|jsx|svg|ts|tsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
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

	optimization: {
		minimize: false,
	},

	plugins: [
		new Dotenv(),
		new WebpackBar(),
		new ForkTsCheckerPlugin({
			async: true,
		}),
		new EslintPlugin({
			extensions: ['js', 'jsx', 'ts', 'tsx'],
		}),
		new DependencyExtractionWebpackPlugin({ injectPolyfill: true }),
		env && env.addons === 'bundleanalyzer' && new BundleAnalyzerPlugin(),
	].filter(Boolean),

	resolve: {
		extensions: ['.js', '.jsx', '.ts', '.tsx'],
	},
});
