import { Icon, Stack } from '@chakra-ui/react';
import colors from 'Config/colors';
import defaultStyle from 'Config/defaultStyle';
import React from 'react';
import ReactSelect, {
	components,
	Props as ReactSelectProps,
} from 'react-select';
import {
	FillInTheBlanks,
	ImageMatching,
	MultipleChoice,
	OpenEndedEssay,
	SingleChoice,
	SortableQuestion,
	YesNo,
} from '../../assets/icons';

interface Props extends ReactSelectProps {}

const Select = React.forwardRef<ReactSelectProps, Props>((props) => {
	const customStyles = {
		control: (provided: any, state: any) => ({
			...provided,
			height: '40px',
			boxShadow: `0 1px 0 ${colors.SHADOW}`,
			borderRadius: defaultStyle.borderRadius,
			borderColor: state.isDisabled
				? colors.BORDER
				: state.isFocused
				? colors.PRIMARY
				: colors.BORDER,
			paddingLeft: '4px',
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

	const renderIcon = (iconName: string) => {
		const iconStyles = {
			fontSize: '1.5rem',
		};
		switch (iconName) {
			case 'FillInTheBlanks':
				return <Icon sx={iconStyles} as={FillInTheBlanks} />;
			case 'ImageMatching':
				return <Icon sx={iconStyles} as={ImageMatching} />;
			case 'MultipleChoice':
				return <Icon sx={iconStyles} as={MultipleChoice} />;
			case 'OpenEndedEssay':
				return <Icon sx={iconStyles} as={OpenEndedEssay} />;
			case 'SingleChoice':
				return <Icon sx={iconStyles} as={SingleChoice} />;
			case 'SortableQuestion':
				return <Icon sx={iconStyles} as={SortableQuestion} />;
			case 'YesNo':
				return <Icon sx={iconStyles} as={YesNo} />;
			default:
				return;
		}
	};

	const SingleValue = (singleValueProps: any) => {
		return (
			<components.SingleValue {...singleValueProps}>
				<Stack direction="row" spacing="2">
					{singleValueProps.data.icon && renderIcon(singleValueProps.data.icon)}
					<span>{singleValueProps.data.label}</span>
				</Stack>
			</components.SingleValue>
		);
	};

	const Option = (optionProps: any) => {
		return (
			<components.Option {...optionProps}>
				<Stack direction="row" spacing="2">
					{optionProps.data.icon && renderIcon(optionProps.data.icon)}
					<span>{optionProps.label}</span>
				</Stack>
			</components.Option>
		);
	};

	return (
		<ReactSelect
			{...props}
			styles={customStyles}
			components={{ Option: Option, SingleValue: SingleValue }}
		/>
	);
});

Select.displayName = 'Select';
export default Select;
