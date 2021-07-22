const path = require('path');

module.exports = {
	paths: {
		entry: {
			backend: path.resolve(process.cwd(), 'assets/js/back-end', 'index.tsx'),
			interactive: path.resolve(
				process.cwd(),
				'assets/js/interactive',
				'index.tsx'
			),
			gettingStarted: path.resolve(
				process.cwd(),
				'assets/js/getting-started',
				'index.tsx'
			),
		},
	},

	resolver: {
		extensions: ['.js', '.jsx', '.ts', '.tsx'],
	},

	devServer: {
		port: 3000,
	},
};
