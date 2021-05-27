import { Icon, Stack } from '@chakra-ui/react';
import colors from 'Config/colors';
import defaultStyle, { BaseLine } from 'Config/defaultStyle';
import React from 'react';
import ReactSelect, {
	Props as ReactSelectProps,
	components,
} from 'react-select';

import { FillInTheBlanks } from '../../assets/icons';

interface Props extends ReactSelectProps {}

const Select = React.forwardRef<ReactSelectProps, Props>((props, ref) => {
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

	const renderIcon = (iconName: string) => {
		if (iconName === 'FillInTheBlanks') {
			return <Icon as={FillInTheBlanks} />;
		}
	};

	const Option = (optionProps: any) => {
		console.log(optionProps);
		return (
			<components.Option {...optionProps}>
				<Stack direction="row" spacing="2">
					{optionProps.data.icon && renderIcon(optionProps.data.icon)}
					<span>{optionProps.label} yo yo</span>
				</Stack>
			</components.Option>
		);
	};

	return (
		<ReactSelect
			{...props}
			styles={customStyles}
			menuIsOpen
			components={{ Option: Option }}
		/>
	);
});

export default Select;
