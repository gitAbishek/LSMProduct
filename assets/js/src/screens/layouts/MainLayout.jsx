import { React } from '@wordpress/element';
import PropTypes from 'prop-types';
import styled from 'styled-components';
import { BaseLine } from '../../config/defaultStyle';
import colors from './../../config/colors';
import Container from '../../components/common/Container';
const MainLayout = (props) => {
	const { children } = props;
	return <MainContainer>{children}</MainContainer>;
};

MainLayout.propTypes = {
	children: PropTypes.object,
};

const MainContainer = styled(Container)`
	padding: ${BaseLine * 6}px;
	margin: ${BaseLine * 6}px auto;
	background-color: ${colors.WHITE};
	box-shadow: 0 0 ${BaseLine * 5}px rgba(0, 0, 0, 0.06);
`;

export default MainLayout;
