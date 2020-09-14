import { React } from '@wordpress/element';
import colors from './../../config/colors';
import styled from 'styled-components';
import Flex from './../../components/common/Flex';
import LogoImg from '../../../../img/logo.png';
import Button from './../../components/common/Button';
import FlexRow from '../../components/common/FlexRow';
import Icon from '../../components/common/Icon';

const MainToolbar = () => {
	return (
		<Header>
			<HeaderLeftContent>
				<LogoContainer>
					<img src={LogoImg} alt="Masteriyo Logo" />
				</LogoContainer>
			</HeaderLeftContent>
			<HeaderRightContent>
				<Button type="primary">This is default</Button>
				<Icon type="ChevronLeft" />
			</HeaderRightContent>
		</Header>
	);
};

export default MainToolbar;

const Header = styled.header`
	display: flex;
	background-color: ${colors.WHITE};
	border-bottom: 1px solid ${colors.BORDER};
`;

const LogoContainer = styled(Flex)`
	max-width: 100px;

	img {
		max-width: 100%;
	}
`;
const HeaderLeftContent = styled(FlexRow)`
	padding: 24px 16px;
`;

const HeaderRightContent = styled(FlexRow)`
	margin-left: auto;
	justify-content: flex-end;
`;
