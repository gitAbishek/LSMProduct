import { React } from '@wordpress/element';
import Flex from './../../components/common/Flex';
import styled from 'styled-components';
import colors from './../../config/colors';

const MainLayout = (props) => {
	<MainContainer>{props.children}</MainContainer>;
};

const MainContainer = styled(Flex)`
	background-color: ${colors.LIGHT};
	box-shadow: 0 0 40px rgba(0, 0, 0, 0.4);
`;

export default MainLayout;
