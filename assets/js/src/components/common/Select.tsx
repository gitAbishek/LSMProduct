import ReactSelect, { Props as ReactSelectProps } from 'react-select';
import defaultStyle, { BaseLine } from 'Config/defaultStyle';

import React from 'react';
import colors from 'Config/colors';

interface Props extends ReactSelectProps {}

const Select: React.FC<Props> = (props) => {
	const customStyles = {
		control: (provided: any, state: any) => ({
			...provided,
			height: BaseLine * 6,
			boxShadow: `0 1px 0 ${colors.SHADOW}`,
			borderRadius: defaultStyle.borderRadius,
			borderColor: state.isDisabled
				? colors.BORDER
				: state.isFocused
				? colors.PRIMARY
				: colors.BORDER,
			paddingLeft: BaseLine,
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
				? colors.PRIMARY
				: 'transparent',
		}),

		menu: (provided: any) => ({
			...provided,
			borderRadius: defaultStyle,
		}),
	};

	return <ReactSelect {...props} styles={customStyles} />;
};

export default Select;
