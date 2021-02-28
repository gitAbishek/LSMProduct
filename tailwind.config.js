module.exports = {
	darkMode: false, // or 'media' or 'class'
	theme: {
		extend: {
			colors: {
				primary: {
					DEFAULT: '#78A6FF',
					50: '#EBF2FF',
					100: '#DEE9FF',
					200: '#C4D8FF',
					300: '#ABC8FF',
					400: '#92B7FF',
					500: '#78A6FF',
					600: '#4584FF',
					700: '#1263FF',
					800: '#004CDE',
					900: '#003AAB',
				},
				accent: {
					DEFAULT: '#FD739C',
					50: '#FFE4EC',
					100: '#FED8E3',
					200: '#FEBED1',
					300: '#FEA5C0',
					400: '#FD8CAE',
					500: '#FD739C',
					600: '#FC4178',
					700: '#FC0E55',
					800: '#D40341',
					900: '#A20232',
				},
			},
			boxShadow: {
				input: '0 1px 0 #EFF0F6',
			},
		},
	},
	variants: {
		extend: {},
	},
	plugins: [require('@tailwindcss/forms')],
	prefix: 'mto-',
};
