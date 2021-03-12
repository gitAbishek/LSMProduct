const { transitionProperty } = require('tailwindcss/defaultTheme');
const defaultTheme = require('tailwindcss/defaultTheme');

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
				secondary: {
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
				textColor: {
					DEFAULT: '#07092F',
				},
				pColor: {
					DEFAULT: '#7C7D8F',
				},
				socialColor: {
					fb: 	'#4267B2',
					gmail: 	'#DD4B39',
					tw: 	'#55ACEE',
					in: 	'#2D7BB6',
					github: '#242A2D',
				},
			},
			boxShadow: {
				input: '0 1px 0 #EFF0F6',
			},
			fontFamily: {
				sans: ['Roboto',defaultTheme.fontFamily.sans]
			},
			maxHeight: {
				       '429': '429px',
					  },
			transitionProperty: {
				'maxHeight': 'maxHeight',
			}
		},
	},
	variants: {
		extend: {
			 display					: 	['responsive','active','group-hover', 'hover', 'focus'],
			 borderWidth				:   ['first','last'],
			 transitionProperty			: 	['responsive','active','group-hover', 'hover', 'focus'],
			 transitionTimingFunction	: 	['responsive','active','group-hover', 'hover', 'focus'],
			 transitionDuration			: 	['responsive','active','group-hover', 'hover', 'focus'],
			 transitionDelay			: 	['responsive','active','group-hover', 'hover', 'focus'],
			 position					: 	['responsive','active','group-hover', 'hover', 'focus'],
			 objectPosition				: 	['responsive','active','group-hover', 'hover', 'focus'],
			 display					: 	['responsive','active','group-hover', 'hover', 'focus'],
			 inset						: 	['responsive','active','group-hover', 'hover', 'focus'],
			 scale						: 	['responsive','active','group-hover', 'hover', 'focus'],
			 transform					: 	['responsive','active','group-hover', 'hover', 'focus'],
			 transformOrigin			: 	['responsive','active','group-hover', 'hover', 'focus'],
			 translate					: 	['responsive','active','group-hover', 'hover', 'focus'],
			 outline					:	['responsive', 'hover','focus-within', 'focus', 'active'],
			 opacity					: ['responsive', 'group-hover', 'focus-within', 'hover', 'focus'],
		},
	},
	plugins: [
		require('@tailwindcss/line-clamp'),
		require('@tailwindcss/forms'),
		require('@tailwindcss/aspect-ratio')
	  ],
	prefix: 'mto-',
};