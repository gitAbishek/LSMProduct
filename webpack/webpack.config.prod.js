const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const Dotenv = require('dotenv-webpack');
const baseConfig = require('./config.base');
const WebpackBar = require('webpackbar');
const ForkTsCheckerPlugin = require('fork-ts-checker-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');
const BundleAnalyzerPlugin =
	require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const PACKAGE = require('../package.json');
const version = PACKAGE.version;

module.exports = (env) => ({
	entry: baseConfig.paths.entry,

	output: {
		filename: `masteriyo-[name].${version}.js`,
		path: baseConfig.paths.output,
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
						loader: MiniCSSExtractPlugin.loader,
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
						loader: MiniCSSExtractPlugin.loader,
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
		splitChunks: {
			cacheGroups: {
				vendor: {
					name: 'dependencies', // part of the bundle name and
					// can be used in chunks array of HtmlWebpackPlugin
					test: /[\\/]node_modules[\\/]/,
					chunks: 'all',
				},
			},
		},
	},

	plugins: [
		new MiniCSSExtractPlugin({ filename: 'masteriyo-[name].css' }),
		new CleanWebpackPlugin(),
		new Dotenv(),
		new WebpackBar(),
		new ForkTsCheckerPlugin({
			async: false,
		}),
		new EslintPlugin({
			extensions: ['js', 'jsx', 'ts', 'tsx'],
		}),
		new DependencyExtractionWebpackPlugin({ injectPolyfill: true }),
		env && env.addons === 'bundleanalyzer' && new BundleAnalyzerPlugin(),
	].filter(Boolean),

	resolve: baseConfig.resolver,
});
