const path          = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		public: path.resolve( process.cwd(), 'assets/js', 'public.js' ),
		admin: path.resolve( process.cwd(), 'assets/js', 'admin.js' ),
	},
	output: {
		filename: '[name].js',
		path: path.resolve( process.cwd(), 'assets/build' )
	}
}
