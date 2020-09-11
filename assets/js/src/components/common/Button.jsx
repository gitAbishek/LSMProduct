import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from './../../config/colors';
import PropTypes from 'prop-types';

const Button = (props) => {
	const { icon, type } = props;
	const buttonColor =
		type === 'primary'
			? colors.PRIMARY
			: type === 'secondary'
			? colors.SECONDARY
			: 'transparent';
	return (
		<StyledButton color={buttonColor} type={type}>
			{icon && <i>{icon}</i>}
			<span>{props.children}</span>
		</StyledButton>
	);
};

Button.propTypes = {
	icon: PropTypes.string,
	type: PropTypes.string,
};

export default Button;

const StyledButton = styled.button`
	cursor: pointer;
	background-color: ${(props) => props.buttonColor || 'transparent'};
	padding: 16px;
	border: 1px solid
		${(props) =>
			props.type === 'primary'
				? colors.PRIMARY
				: props.type === 'secondary'
				? colors.SECONDARY
				: colors.BORDER};
	color: ${(props) =>
		props.type === 'primary'
			? colors.LIGHT
			: props.type === 'secondary'
			? colors.LIGHT
			: colors.TEXT};
`;
