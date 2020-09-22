import { React } from '@wordpress/element';
import styled, { css } from 'styled-components';
import colors from './../../config/colors';
import PropTypes from 'prop-types';
import { lighten } from 'polished';
import fontSize from '../../config/fontSize';
import defaultStyle, { BaseLine } from '../../config/defaultStyle';
import Icon from './Icon';

const Button = (props) => {
	const { icon, type, size, children } = props;
	return (
		<StyledButton type={type} size={size} {...props}>
			{icon && <Icon icon={icon} />}
			<span>{children}</span>
		</StyledButton>
	);
};

Button.propTypes = {
	icon: PropTypes.object,
	type: PropTypes.string,
	size: PropTypes.string,
	children: PropTypes.any,
};

export default Button;

const StyledButton = styled.button`
	cursor: pointer;
	transition: all 0.35s ease-in-out;
	border: 1px solid ${colors.BORDER};
	padding: 12px 16px;
	font-weight: 500;
	font-size: ${fontSize.SMALL};
	border-radius: ${defaultStyle.borderRadius};
	background-color: ${colors.WHITE};
	color: ${colors.TEXT};
	line-height: 1;
	display: flex;
	align-items: center;
	outline: none;

	i {
		margin-right: ${BaseLine}px;
	}

	/* Button Types */
	${(props) =>
		(props.type === 'primary' &&
			css`
				color: ${colors.WHITE};
				border-color: ${colors.PRIMARY};
				background-color: ${colors.PRIMARY};

				&:hover {
					background-color: ${lighten(0.05, colors.PRIMARY)};
				}
			`) ||
		(props.type === 'secondary' &&
			css`
				color: ${colors.WHITE};
				border-color: ${colors.SECONDARY};
				background-color: ${colors.SECONDARY};

				&:hover {
					background-color: ${lighten(0.05, colors.SECONDARY)};
				}
			`) ||
		css`
			&:hover {
				color: ${colors.PRIMARY};
				border-color: ${colors.PRIMARY};
			}
		`};

	/* Button Size */
	${(props) =>
		(props.size === 'small' &&
			css`
				padding: 6px 8px;
				font-size: ${fontSize.SMALL};
			`) ||
		(props.size === 'large' &&
			css`
				padding: 14px 16px;
				font-size: ${fontSize.LARGE};
			`)}
`;
