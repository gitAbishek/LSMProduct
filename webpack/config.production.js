const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const Dotenv = require('dotenv-webpack');
const baseConfig = require('./config.base');
const WebpackBar = require('webpackbar');
const ForkTsCheckerPlugin = require('fork-ts-checker-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');

const config = {
	entry: baseConfig.paths.entry,

	output: {
		filename: 'masteriyo-[name].js',
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
						plugins: [
							[
								require.resolve('@wordpress/babel-plugin-makepot'),
								{ output: 'i18n/languages/masteriyo.pot' },
							],
						].filter(Boolean),
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
						loader: MiniCSSExtractPlugin.loader,
					},
					{
						loader: 'css-loader',
					},
				],
			},
		],
	},

	optimization: {
		minimize: true,
		minimizer: [new TerserPlugin()],
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
		new DependencyExtractionWebpackPlugin({
			injectPolyfill: true,
		}),
	].filter(Boolean),

	resolve: baseConfig.resolver,
};

module.exports = config;
