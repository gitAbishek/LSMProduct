import { React } from '@wordpress/element';
import ReactSelect from 'react-select';
import colors from '../../config/colors';
import defaultStyle, { BaseLine } from '../../config/defaultStyle';

const Select = (props) => {
	const customStyles = {
		control: (provided) => ({
			...provided,
			height: BaseLine * 6,
			boxShadow: `0 1px 0 ${colors.SHADOW}`,
			border: `1px solid ${colors.BORDER}`,
			borderRadius: defaultStyle.borderRadius,
		}),
		placeholder: (provided) => ({
			...provided,
			color: colors.PLACEHOLDER,
			marginLeft: BaseLine,
		}),
		indicatorSeparator: (provided) => ({
			...provided,
			backgroundColor: colors.BORDER,
		}),
	};
	return <ReactSelect {...props} styles={customStyles} />;
};

export default Select;
