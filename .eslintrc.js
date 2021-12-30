module.exports = {
	globals: {
		wp: true,
	},
	rules: {
		'react-hooks/rules-of-hooks': 'error',
		'react-hooks/exhaustive-deps': 'warn',
		'react/prop-types': 'off',
		'no-unused-vars': process.env.NODE_ENV === 'production' ? 'error' : 'off',
		'max-len': [
			'error',
			{
				code: 500,
			},
		],
	},
	parser: '@typescript-eslint/parser',
	extends: ['plugin:react/recommended'],
	settings: {
		react: {
			version: 'detect',
		},
		'import/resolver': {
			typescript: {},
			webpack: {
				config: 'webpack.config.js',
			},
		},
	},
	plugins: ['import', '@typescript-eslint', 'react-hooks'],
};
