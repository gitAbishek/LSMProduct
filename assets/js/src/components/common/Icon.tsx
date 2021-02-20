import React from 'react';
import styled from 'styled-components';

interface Props {
	icon?: any;
	size?: string;
	color?: string;
}

const Icon: React.FC<Props> = (props) => {
	const { icon, size, color } = props;
	return (
		<StyledIcon size={size} color={color} {...props}>
			{icon}
		</StyledIcon>
	);
};

const StyledIcon = styled.i`
	display: flex;
	font-size: ${(props: Props) => props.size || 'inherit'};
	color: ${(props: Props) => props.color || 'inherit'};
`;

export default Icon;
