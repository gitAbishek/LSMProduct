const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { isProduction, isDevelopment } = require('webpack-mode');
const ReactRefreshWebpackPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const ErrorOverlayPlugin = require('error-overlay-webpack-plugin');
const Dotenv = require('dotenv-webpack');

const config = {
	entry: {
		app: path.resolve(process.cwd(), 'assets/js/src', 'index.tsx'),
	},

	output: {
		filename: '[name].js',
		path: path.resolve(process.cwd(), 'assets/js/build'),
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
							isDevelopment && require.resolve('react-refr`esh/babel'),
							isProduction && [
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
					limit: 10000,
					name: 'static/media/[name].[hash:8].[ext]',
				},
			},

			{
				test: /\.css$/i,
				exclude: /node_modules/,
				use: [
					{
						loader: isProduction ? MiniCSSExtractPlugin.loader : 'style-loader',
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
		isDevelopment &&
			new ReactRefreshWebpackPlugin({
				overlay: false,
			}),
		isDevelopment && new ErrorOverlayPlugin(),
		new Dotenv(),
	].filter(Boolean),

	resolve: {
		extensions: ['.js', '.jsx', '.ts', '.tsx'],
		alias: {
			Components: path.resolve(process.cwd(), 'assets/js/src/components'),
			Config: path.resolve(process.cwd(), 'assets/js/src/config'),
			Icons: path.resolve(process.cwd(), 'assets/js/src/assets/icons'),
			Layouts: path.resolve(process.cwd(), 'assets/js/src/screens/layouts'),
		},
	},

	devServer: {
		port: 3000,
	},

	devtool: isDevelopment ? 'cheap-module-source-map' : false,
};
module.exports = config;
