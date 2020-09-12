import { React } from '@wordpress/element';
import colors from './../../config/colors';
import styled from 'styled-components';
import Flex from './../../components/common/Flex';
import LogoImg from '../../../../img/logo.png';
import Button from './../../components/common/Button';

const MainToolbar = () => {
	return (
		<Header>
			<HeaderLeftContent>
				<LogoContainer>
					<img src={LogoImg} alt="Masteriyo Logo" />
				</LogoContainer>
			</HeaderLeftContent>
			<HeaderRightContent>
				<Button>This is default</Button>
			</HeaderRightContent>
		</Header>
	);
};

export default MainToolbar;

const Header = styled.header`
	display: flex;
	border-bottom: 1px solid ${colors.BORDER};
`;

const LogoContainer = styled(Flex)`
	max-width: 100px;

	img {
		max-width: 100%;
	}
`;
const HeaderLeftContent = styled(Flex)`
	padding: 24px 16px;
`;

const HeaderRightContent = styled(Flex)`
	margin-left: auto;
	justify-content: flex-end;
`;
