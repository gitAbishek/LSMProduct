import React from 'react';
import styled from 'styled-components';

interface Props extends React.ComponentProps<any> {
	icon?: any;
}

const Icon: React.FC<Props> = (props) => {
	const { icon } = props;
	return <StyledIcon {...props}>{icon}</StyledIcon>;
};

const StyledIcon = styled.i`
	display: flex;
	font-size: inherit;
	color: inherit;

	svg {
		height: 1em;
		width: 1em;
		fill: currentColor;
	}
`;

export default Icon;
