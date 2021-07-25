import theme from '../theme/theme';
import colors from './colors';
import defaultStyle from './defaultStyle';

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
	control: (provided: any, state: any) => ({
		...provided,
		minHeight: '40px',
		boxShadow: theme.shadows.input,
		borderRadius: defaultStyle.borderRadius,
		borderColor: state.isDisabled
			? colors.BORDER
			: state.isFocused
			? colors.PRIMARY
			: colors.BORDER,
		transition: 'all 0.35s ease-in-out',
		backgroundColor: state.isDisabled
			? colors.LIGHT_GRAY
			: state.isFocused
			? colors.LIGHT_BLUEISH_GRAY
			: colors.WHITE,
		'&:hover': {
			borderColor: colors.PRIMARY,
		},
	}),

	placeholder: (provided: any) => ({
		...provided,
		color: colors.PLACEHOLDER,
		marginLeft: 0,
	}),

	indicatorSeparator: (provided: any) => ({
		...provided,
		backgroundColor: colors.BORDER,
	}),

	option: (provided: any, state: any) => ({
		...provided,
		backgroundColor: state.isSelected
			? colors.PRIMARY
			: state.isFocused
			? '#ccddff'
			: 'transparent',
	}),

	menu: (provided: any) => ({
		...provided,
		borderRadius: defaultStyle,
		zIndex: '3',
	}),
};
