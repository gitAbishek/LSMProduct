const path = require('path');

module.exports = {
	paths: {
		entry: {
			backend: path.resolve(process.cwd(), 'assets/js/back-end', 'index.tsx'),
		},
		output: path.resolve(process.cwd(), 'assets/js/build'),
	},

	resolver: {
		extensions: ['.js', '.jsx', '.ts', '.tsx'],
		alias: {
			Components: path.resolve(process.cwd(), 'assets/js/back-end/components'),
			Config: path.resolve(process.cwd(), 'assets/js/back-end/config'),
			Icons: path.resolve(process.cwd(), 'assets/js/back-end/assets/icons'),
			Layouts: path.resolve(
				process.cwd(),
				'assets/js/back-end/screens/layouts'
			),
		},
	},

	devServer: {
		port: 3000,
	},
};
