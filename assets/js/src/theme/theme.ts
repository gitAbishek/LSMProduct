import { extendTheme } from '@chakra-ui/react';

const theme = extendTheme({
	colors: {
		blue: {
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
	},
	styles: {
		global: {
			'html,body': {
				bg: 'gray.50',
			},
			'::placeholder': {
				fontSize: 'sm',
			},
		},
	},
	shadows: {
		box: '0px 0px 60px rgba(0, 0, 0, 0.06)',
		input: '0px 1px 0px #EFF0F6',
	},
	components: {
		Popover: {
			baseStyle: {
				popper: {
					width: 'fit-content',
					maxWidth: 'fit-content',
				},
			},
		},
		Button: {
			baseStyle: {
				borderRadius: 'sm',
			},
			sizes: {
				md: {
					fontSize: 'xs',
				},
			},
		},
		Input: {
			sizes: {
				md: {
					field: {
						borderRadius: 'sm',
						shadow: 'input',
					},
				},
			},

			defaultProps: {
				_placeholder: {
					color: 'red',
				},
			},
		},
		Textarea: {
			sizes: {
				md: {
					borderRadius: 'sm',
					shadow: 'input',
				},
			},
		},
		FormLabel: {
			baseStyle: {
				fontSize: 'sm',
				fontWeight: 'semibold',
				mb: '3',
			},
		},
		Checkbox: {
			defaultProps: {
				colorScheme: 'primary',
			},
		},
	},
});

export default theme;
