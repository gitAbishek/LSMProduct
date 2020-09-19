import { React } from '@wordpress/element';
import ReactSelect from 'react-select';
import colors from '../../config/colors';
import defaultStyle, { BaseLine } from '../../config/defaultStyle';
import { lighten } from 'polished';

const Select = (props) => {
	const customStyles = {
		control: (provided, state) => ({
			...provided,
			height: BaseLine * 6,
			boxShadow: `0 1px 0 ${colors.SHADOW}`,
			borderRadius: defaultStyle.borderRadius,
			borderColor: state.isDisabled
				? colors.DISABLED
				: state.isFocused
				? colors.PRIMARY
				: colors.BORDER,
			paddingLeft: BaseLine,
			transition: 'all 0.35s ease-in-out',

			'&:hover': {
				borderColor: colors.PRIMARY,
			},
		}),

		placeholder: (provided) => ({
			...provided,
			color: colors.PLACEHOLDER,
			marginLeft: 0,
		}),

		indicatorSeparator: (provided) => ({
			...provided,
			backgroundColor: colors.BORDER,
		}),

		option: (provided, state) => ({
			...provided,
			backgroundColor: state.isSelected
				? colors.PRIMARY
				: state.isFocused
				? lighten(0.11, colors.PRIMARY)
				: 'transparent',
		}),

		menu: (provided) => ({
			...provided,
			borderRadius: defaultStyle,
		}),
	};

	return <ReactSelect {...props} styles={customStyles} />;
};

export default Select;
