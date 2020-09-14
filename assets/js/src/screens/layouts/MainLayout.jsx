import { React } from '@wordpress/element';
import Flex from './../../components/common/Flex';
import styled from 'styled-components';
import colors from './../../config/colors';
import PropTypes from 'prop-types';
import { BaseLine } from '../../config/defaultStyle';

const MainLayout = (props) => {
	const { children } = props;
	return <MainContainer>{children}</MainContainer>;
};

MainLayout.propTypes = {
	children: PropTypes.object,
};

const MainContainer = styled(Flex)`
	padding: ${BaseLine * 6}px;
	margin: ${BaseLine * 6}px auto;
	max-width: 1140px;
	background-color: ${colors.WHITE};
	box-shadow: 0 0 ${BaseLine * 5}px rgba(0, 0, 0, 0.06);
`;

export default MainLayout;
