import { BaseLine } from 'Config/defaultStyle';
import Container from 'Components/common/Container';
import PropTypes from 'prop-types';
import { React } from '@wordpress/element';
import colors from 'Config/colors';
import styled from 'styled-components';

const MainLayout = (props) => {
	const { children } = props;
	return (
		<Container>
			<MainContainer>{children}</MainContainer>
		</Container>
	);
};

MainLayout.propTypes = {
	children: PropTypes.object,
};

const MainContainer = styled(Container)`
	padding: ${BaseLine * 6}px;
	margin-top: ${BaseLine * 6}px;
	background-color: ${colors.WHITE};
	box-shadow: 0 0 ${BaseLine * 5}px rgba(0, 0, 0, 0.06);
`;

export default MainLayout;
