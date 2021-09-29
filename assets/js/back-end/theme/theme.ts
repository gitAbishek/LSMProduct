import { extendTheme } from '@chakra-ui/react';
import { StepsStyleConfig as Steps } from 'chakra-ui-steps';

const theme = extendTheme({
	colors: {
		blue: {
			10: '#ecf3ff',
			50: '#FFFFFF',
			100: '#FFFFFF',
			200: '#DEE9FF',
			300: '#ABC7FF',
			400: '#78A6FF',
			500: '#4584FF',
			600: '#1262FF',
			700: '#004BDE',
			800: '#003AAB',
			900: '#002978',
		},
	},
	styles: {
		global: {
			'html,body': {
				bg: 'gray.50',
			},
			'#masteriyo, #masteriyo-onboarding, #masteriyo-interactive-course': {
				'input[type="color"], input[type="date"], input[type="datetime-local"], input[type="datetime"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], input[type="week"], select, textarea, #rfs-btn':
					{
						shadow: 'input',
						color: 'gray.600',
						border: '1px',
						rounded: 'sm',
						borderColor: 'gray.200',
						maxW: 'auto',
						_hover: {
							borderColor: 'gray.300',
						},

						_focus: {
							borderColor: 'blue.300',
						},
					},
				ul: {
					li: {
						marginBottom: 0,
					},
				},
				'.ProseMirror, .masteriyo-interactive-description': {
					'h1, h2, h3, h4, h5, h6': {
						lineHeight: '1.1',
						fontWeight: '700',
					},
					h1: {
						fontSize: '28px',
						my: '0.67em',
					},
					h2: {
						fontSize: '24px',
						my: '0.75em',
					},
					h3: {
						fontSize: '20px',
						my: '0.83em',
					},
					h4: {
						fontSize: '18px',
						my: '1.12em',
					},
					h5: {
						fontSize: '16px',
						my: '1.5em',
					},
					h6: {
						fontSize: '14px',
						my: '1.67em',
					},
					p: {
						mb: '1rem',
					},
					blockquote: {
						paddingLeft: '1rem',
						borderLeft: '2px solid rgba(13,13,13,.1)',
					},
					code: {
						backgroundColor: 'gray.100',
						color: 'gray.600',
						padding: '.1rem .3rem',
						borderRadius: '4px',
					},
					'ul, ol': {
						padding: '0 0.75rem',
					},
					ul: {
						listStyleType: 'disc',
					},
					ol: {
						pl: '1.2rem',
					},
					pre: {
						background: '#0D0D0D',
						color: '#FFF',
						fontFamily: 'JetBrainsMono, monospace',
						padding: '0.75rem 1rem',
						borderRadius: '0.5rem',
						code: {
							color: 'inherit',
							padding: '0',
							background: 'none',
							fontSize: '0.8rem',
						},
						'.hljs-comment,.hljs-quote': {
							color: 'gray.600',
						},
						'.hljs-variable,.hljs-template-variable,.hljs-attribute,.hljs-tag,.hljs-name,.hljs-regexp,.hljs-link,.hljs-name,.hljs-selector-id,.hljs-selector-class':
							{
								color: '#F98181',
							},
						'.hljs-number,.hljs-meta,.hljs-built_in,.hljs-builtin-name,.hljs-literal,.hljs-type,.hljs-params':
							{
								color: '#FBBC88',
							},
						'.hljs-string,.hljs-symbol,.hljs-bullet': {
							color: '#B9F18D',
						},
						'.hljs-title,.hljs-section': {
							color: '#FAF594',
						},
						'.hljs-keyword,.hljs-selector-tag': {
							color: '#70CFF8',
						},
						'.hljs-emphasis': {
							fontStyle: 'italic',
						},
						'.hljs-strong': {
							fontWeight: '700',
						},
					},
				},
			},
			'.wp-admin #masteriyo': {
				ml: '-20px',
			},
			'.admin-bar': {
				' .masteriyo-interactive-header': {
					top: '32px !important',
				},
			},
			'#masteriyo': {
				select: {
					fontSize: 'sm',
					borderRadius: 'sm',
					shadow: 'input',
					borderColor: 'inherit',
					maxWidth: 'full',
					color: 'inherit',
					bg: 'white',
					_hover: {
						borderColor: 'gray.300',
					},
				},
			},
			'::placeholder': {
				fontSize: 'sm',
			},
			'.responsiveTable': {
				width: '100%',
				th: {
					pb: '6',
					borderBottom: 'none',
					textTransform: 'uppercase',
					fontSize: 'xs',
					textAlign: 'left',
				},
				'tr:nth-of-type(2n+1) td': {
					bg: '#f8f9fa',
				},

				tr: {
					'th, td': {
						':first-of-type': {
							pl: '12',
						},
						':last-child': {
							pr: '6',
						},
					},
				},
				td: {
					py: '3',
					borderBottom: 'none',
				},
			},
			'.responsiveTable td .tdBefore': { display: 'none' },
			'@media screen and (max-width: 40em)': {
				'.responsiveTable table': { display: 'block' },
				'.responsiveTable thead': { display: 'block' },
				'.responsiveTable tbody': { display: 'block' },
				'.responsiveTable th': {
					display: 'block',
				},
				'.responsiveTable td': { display: 'block' },
				'.responsiveTable tr': {
					display: 'block',
				},
				'.responsiveTable thead tr': {
					position: 'absolute',
					top: '-9999px',
					left: '-9999px',
					borderBottom: '2px solid #333',
				},

				'.responsiveTable td.pivoted': {
					border: 'none !important',
					position: 'relative',
					paddingLeft: 'calc(50% + 10px) !important',
					textAlign: 'left !important',
					whiteSpace: 'pre-wrap',
					overflowWrap: 'break-word',
					pr: '6',
				},
				'.responsiveTable td .tdBefore': {
					position: 'absolute',
					display: 'block',
					left: '6',
					width: 'calc(50% - 20px)',
					whiteSpace: 'pre-wrap',
					overflowWrap: 'break-word',
					textAlign: 'left !important',
					fontWeight: '600',
					fontSize: 'sm',
				},
			},

			'.react-datepicker-wrapper,\n.react-datepicker__input-container': {
				display: 'block',
			},

			'.react-datepicker__header': {
				borderRadius: '0',
				background: 'blue.100',
			},

			'.react-datepicker__navigation': {
				top: '8px',
			},
			'.react-datepicker,\n.react-datepicker__header,\n.react-datepicker__time-container':
				{
					borderColor: '#e2e8f0',
				},
			'.react-datepicker__current-month,\n.react-datepicker-time__header,\n.react-datepicker-year-header':
				{
					fontSize: 'inherit',
					fontWeight: 600,
				},
			'.react-datepicker__time-container .react-datepicker__time .react-datepicker__time-box ul.react-datepicker__time-list li.react-datepicker__time-list-item':
				{
					margin: '0 1px 0 0',
					height: 'auto',
					padding: '7px 10px',
					'&:hover': { background: '#edf2f7' },
				},
			'.react-datepicker__day:hover': { background: '#edf2f7' },
			'.react-datepicker__day--selected,\n.react-datepicker__day--in-selecting-range,\n.react-datepicker__day--in-range,\n.react-datepicker__month-text--selected,\n.react-datepicker__month-text--in-selecting-range,\n.react-datepicker__month-text--in-range,\n.react-datepicker__time-container .react-datepicker__time .react-datepicker__time-box ul.react-datepicker__time-list li.react-datepicker__time-list-item--selected':
				{
					background: '#3182ce',
					fontWeight: 'normal',
					'&:hover': { background: '#2a69ac' },
				},
			'.react-datepicker__triangle': {
				d: 'none',
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
						bg: '#f8f8f8',
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

		Modal: {
			sizes: {
				fullSpacing: {
					dialog: {
						w: '100%',
						maxW: '800px',
						minH: '400px',
						maxH: 'calc(100vh - 80px)',
					},
				},
			},
		},

		Radio: {
			sizes: {
				md: {
					label: {
						fontSize: 'sm',
					},
				},
			},
		},

		Steps,
	},
});

export default theme;
