import defaultStyle, { BaseLine } from 'Config/defaultStyle';
import styled, { css } from 'styled-components';

import Icon from './Icon';
import React from 'react';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import { lighten } from 'polished';

interface Props {
	icon?: any;
	appearance?: 'default' | 'primary' | 'secondary';
	size?: 'small' | 'medium' | 'large';
}

const Button: React.FC<Props> = (props) => {
	const { icon, appearance, size } = props;
	return (
		<StyledButton appearance={appearance} size={size} {...props}>
			{icon && <Icon icon={icon} />}
			<span>{props.children}</span>
		</StyledButton>
	);
};

Button.defaultProps = {
	appearance: 'default',
	size: 'medium',
};

export default Button;

const StyledButton = styled.button`
	cursor: pointer;
	transition: all 0.35s ease-in-out;
	border: 1px solid transparent;
	font-weight: 500;
	font-size: ${fontSize.SMALL};
	border-radius: ${defaultStyle.borderRadius};
	line-height: 1;
	display: flex;
	align-items: center;
	outline: none;

	i {
		margin-right: ${BaseLine}px;
	}

	${(props: Props) =>
		props.appearance === 'default' &&
		css`
			color: ${colors.TEXT};
			border-color: ${colors.BORDER};
			background-color: ${colors.WHITE};

			&:hover {
				background-color: ${lighten(0.05, colors.PRIMARY)};
			}
		`}

	${(props: Props) =>
		props.appearance === 'primary' &&
		css`
			color: ${colors.WHITE};
			border-color: ${colors.PRIMARY};
			background-color: ${colors.PRIMARY};

			&:hover {
				background-color: ${lighten(0.05, colors.PRIMARY)};
			}
		`}

	${(props: Props) =>
		props.appearance === 'secondary' &&
		css`
			color: ${colors.WHITE};
				border-color: ${colors.SECONDARY};
				background-color: ${colors.SECONDARY};

				&:hover {
					background-color: ${lighten(0.05, colors.SECONDARY)};
				}
			}
		`}
		
	${(props: Props) =>
		props.size === 'medium' &&
		css`
			padding: 12px 16px;
		`}

	${(props: Props) =>
		props.size === 'small' &&
		css`
			padding: 6px 8px;
			font-size: ${fontSize.SMALL};
		`}
	
	${(props: Props) =>
		props.size === 'large' &&
		css`
			padding: 14px 16px;
			font-size: ${fontSize.LARGE};
		`}
`;
