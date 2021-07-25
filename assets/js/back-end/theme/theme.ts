import { extendTheme } from '@chakra-ui/react';
import { StepsStyleConfig as Steps } from 'chakra-ui-steps';

const theme = extendTheme({
	colors: {
		blue: {
			10: '#ecf3ff',
			50: '#b5ceff',
			100: '#a2c2ff',
			200: '#7da9ff',
			300: '#6a9dff',
			400: '#5890ff',
			500: '#4584ff',
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
			'#masteriyo': {
				'input[type="color"], input[type="date"], input[type="datetime-local"], input[type="datetime"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], input[type="week"], select, textarea, #rfs-btn':
					{
						shadow: 'input',
						color: 'gray.600',
						border: '1px',
						rounded: 'sm',
						borderColor: 'gray.200',
						_hover: {
							borderColor: 'gray.300',
						},

						_focus: {
							borderColor: 'blue.300',
						},
					},
			},
			'.wp-admin #masteriyo': {
				ml: '-20px',
			},
			'.admin-bar .masteriyo-interactive-header': {
				top: '32px !important',
			},
			'#masteriyo select': {
				fontSize: 'sm',
				borderRadius: 'sm',
				shadow: 'input',
				borderColor: 'inherit',
				color: 'inherit',
				bg: 'white',
				_hover: {
					borderColor: 'gray.300',
				},
			},
			'::placeholder': {
				fontSize: 'sm',
			},
		},
	},
	shadows: {
		box: '0px 0px 60px rgba(0, 0, 0, 0.08)',
		input: '0px 1px 0px #EFF0F6',
		button: '0px 4px 14px rgba(0, 0, 0, 0.13)',
		boxl: '0px 0px 60px rgba(0, 0, 0, 0.15)',
		header: '0px 2px 15px rgba(0, 0, 0, 0.04)',
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
				sm: {
					fontSize: 'xs',
				},
			},
			variants: {
				solid: {
					shadow: 'button',
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

			variants: {
				outline: {
					addon: {
						bg: 'blue.10',
						borderRadius: 'sm',
						fontSize: 'sm',
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
		Select: {
			sizes: {
				md: {
					field: {
						borderRadius: 'sm',
						shadow: 'input',
					},
				},
			},
		},

		Steps,
	},
});

export default theme;
