import { BaseLine } from 'Config/defaultStyle';
import Container from 'Components/common/Container';
import React from 'react';
import colors from 'Config/colors';
import styled from 'styled-components';

interface Props {}

const MainLayout: React.FC<Props> = (props) => {
	return (
		<Container>
			<MainContainer>{props.children}</MainContainer>
		</Container>
	);
};

const MainContainer = styled(Container)`
	padding: ${BaseLine * 6}px;
	margin-top: ${BaseLine * 6}px;
	background-color: ${colors.WHITE};
	box-shadow: 0 0 ${BaseLine * 5}px rgba(0, 0, 0, 0.06);
`;

export default MainLayout;
