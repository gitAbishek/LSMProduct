import * as React from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../config/colors';

const Header = () => {
	return <HeaderContainer></HeaderContainer>;
};

const HeaderContainer = styled.header`
	background-color: ${colors.LIGHT};
	border-bottom: 1px solid ${colors.BORDER};
`;

export default Header;
