const ReactRefreshWebpackPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const Dotenv = require('dotenv-webpack');
const baseConfig = require('./config.base');
const WebpackBar = require('webpackbar');
const ForkTsCheckerPlugin = require('fork-ts-checker-webpack-plugin');
const EslintPlugin = require('eslint-webpack-plugin');

module.exports = (env) => ({
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
		new ReactRefreshWebpackPlugin(),
		new Dotenv(),
		new WebpackBar(),
		new ForkTsCheckerPlugin({
			async: true,
		}),
		new EslintPlugin({
			extensions: ['js', 'jsx', 'ts', 'tsx'],
		}),
	].filter(Boolean),

	resolve: baseConfig.resolver,
	devServer: {
		headers: { 'Access-Control-Allow-Origin': '*' },
		allowedHosts: [env.WORDPRESS_URL],
		host: 'localhost',
		port: 3000,
	},
});
