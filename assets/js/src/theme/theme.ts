import { extendTheme } from '@chakra-ui/react';

const theme = extendTheme({
	colors: {
		blue: {
			50: '#f2f8ff',
			100: '#dbeafe',
			200: '#bfdbfe',
			300: '#93c5fd',
			400: '#60a5fa',
			500: '#3b82f6',
			600: '#2563eb',
			700: '#1d4ed8',
			800: '#1e40af',
			900: '#1e3a8a',
		},
	},
	styles: {
		global: {
			'html,body': {
				bg: 'gray.50',
			},
		},
	},
	shadows: {
		box: '0px 0px 60px rgba(0, 0, 0, 0.06)',
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
		},
		Input: {
			sizes: {
				md: {
					field: {
						borderRadius: 'sm',
					},
				},
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
