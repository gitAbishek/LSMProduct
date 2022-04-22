import theme from '../theme/theme';

export const borderedBoxStyles = {
	border: '1px',
	borderColor: 'gray.100',
	rounded: 'sm',
	mb: '4',
	alignItems: 'center',
	justify: 'space-between',
	px: '2',
	py: '1',
};

export const sectionHeaderStyles = {
	alignItems: 'center',
	justifyContent: 'space-between',
	borderBottom: '1px',
	borderColor: 'gray.100',
	pb: '3',
};

export const infoIconStyles = {
	d: 'inline-flex',
	alignItems: 'center',
	ml: '2',
	color: 'gray.400',
};

export const reactSelectStyles = {
	valueContainer: (provided: any) => ({
		...provided,
		paddingTop: 0,
		paddingBottom: 0,
	}),

	control: (provided: any, state: any) => ({
		...provided,
		minHeight: '40px',
		minWidth: '300px',
		boxShadow: theme.shadows.input,
		borderRadius: theme.radii.sm,
		borderColor: state.isDisabled
			? theme.colors.gray[200]
			: state.isFocused
			? theme.colors.blue
			: 'inherit',
		transition: 'all 0.35s ease-in-out',
		backgroundColor: theme.colors.white,
		opacity: state.isDisabled ? '0.4' : '1',
		cursor: state.isDisabled ? 'not-allowed' : 'inherit',
		fontSize: theme.fontSizes.sm,

		'&:hover': {
			borderColor: theme.colors.gray[300],
		},
	}),

	placeholder: (provided: any) => ({
		...provided,
		color: theme.colors.gray[300],
		marginLeft: 0,
	}),

	indicatorSeparator: (provided: any) => ({
		...provided,
		backgroundColor: theme.colors.gray[100],
	}),

	dropdownIndicator: (provided: any) => ({
		...provided,
		color: theme.colors.gray[300],
	}),

	option: (provided: any, state: any) => ({
		...provided,
		backgroundColor: state.isSelected
			? theme.colors.blue[300]
			: state.isFocused
			? theme.colors.blue[10]
			: 'transparent',
		color: state.data?.isDisabled ? '#1a202c' : provided.color,
	}),

	multiValue: (provided: any) => ({
		...provided,
		backgroundColor: theme.colors.blue[50],
	}),

	menu: (provided: any) => ({
		...provided,
		borderRadius: theme.radii.sm,
		zIndex: '3',
		fontSize: theme.fontSizes.xs,
	}),
};

export const whileDraggingStyles = {
	bg: '#f8f8f8',
	border: '1px dashed',
	borderColor: 'gray.200',
	borderRadius: 'sm',
};

export const tableStyles = {
	th: {
		pb: '6',
		borderBottom: 'none',
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
};

export const navLinkStyles = {
	mr: '10',
	py: '6',
	d: 'flex',
	alignItems: 'center',
	fontWeight: 'medium',
	fontSize: ['xs', null, 'sm'],
};

export const navActiveStyles = {
	borderBottom: '2px',
	borderColor: 'blue.500',
	color: 'blue.500',
};

export const tabStyles = {
	justifyContent: 'flex-start',
	width: [null, null, '180px'],
	borderLeft: 0,
	borderRight: '2px solid',
	borderRightColor: 'transparent',
	marginLeft: 0,
	marginRight: '-2px',
	pl: 0,
	fontSize: ['xs', null, 'sm'],
	textAlign: 'left',
};

export const tabListStyles = {
	borderLeft: 0,
	borderRight: '2px solid',
	borderRightColor: 'gray.200',
};

export const accountStyles = {
	'#masteriyo-account-container': {
		p: {
			mb: 0,
		},
		ul: {
			li: {
				a: {
					textDecoration: 'none',
					color: 'gray.700',
					':hover': {
						color: 'blue.500',
					},
					'&.active': {
						color: 'blue.500',
					},
				},
			},
		},

		table: {
			tr: {
				'td, th': {
					border: 'none',
					px: '2',
					a: {
						textDecoration: 'none',
					},
				},
			},
		},

		'.chakra-tabs__tablist': {
			button: {
				bg: 'none',
			},
		},

		'.copy-from': {
			bg: 'none',
		},
	},
};
