import { React } from '@wordpress/element';
import styled, { css } from 'styled-components';
import colors from './../../config/colors';
import PropTypes from 'prop-types';
import { lighten } from 'polished';
import fontSize from '../../config/fontSize';
<<<<<<< HEAD
import defaultStyle from '../../config/defaultStyle';
=======
>>>>>>> a1e3e49a48887a017439096b352cd8b6635255b0

const Button = (props) => {
	const { icon, type, size, children } = props;
	return (
		<StyledButton type={type} size={size}>
			{icon && <i>{icon}</i>}
			<span>{children}</span>
		</StyledButton>
	);
};

Button.propTypes = {
	icon: PropTypes.string,
	type: PropTypes.string,
	size: PropTypes.string,
	children: PropTypes.any,
};

export default Button;

const StyledButton = styled.button`
	cursor: pointer;
	transition: all 0.35s ease-in-out;
	border: 1px solid ${colors.border};
	padding: 10px 12px;
	font-weight: bold;
<<<<<<< HEAD
	font-size: ${fontSize.SMALL};
	border-radius: ${defaultStyle.borderRadius};

	&:hover {
		color: ${colors.PRIMARY};
		border-color: ${colors.PRIMARY};
	}

	background-color: ${colors.WHITE};
=======

>>>>>>> a1e3e49a48887a017439096b352cd8b6635255b0
	${(props) =>
		props.type === 'primary' &&
		css`
			color: ${colors.WHITE};
			border-color: ${colors.PRIMARY};
			background-color: ${colors.PRIMARY};

			&:hover {
				background-color: ${lighten(0.05, colors.PRIMARY)};
			}
		`}
	${(props) =>
		props.type === 'secondary' &&
		css`
			color: ${colors.WHITE};
			border-color: ${colors.SECONDARY};
			background-color: ${colors.SECONDARY};

			&:hover {
				background-color: ${lighten(0.05, colors.SECONDARY)};
			}
<<<<<<< HEAD
		`};
=======
		`}
>>>>>>> a1e3e49a48887a017439096b352cd8b6635255b0
`;
