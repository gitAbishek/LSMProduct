import { React } from '@wordpress/element';
import { BiShow } from 'react-icons/bi';
import styled from 'styled-components';
import LogoImg from '../../../../img/logo.png';
import { ContainerFluid } from '../../components/common/Container';
import FlexRow from '../../components/common/FlexRow';
import { BaseLine } from '../../config/defaultStyle';
import Button from './../../components/common/Button';
import Flex from './../../components/common/Flex';
import colors from './../../config/colors';
import Menu, { MenuItem } from '../../components/common/Menu';

const MainToolbar = () => {
	return (
		<Header>
			<ContainerFluid>
				<HeaderLeftContent>
					<LogoContainer>
						<img src={LogoImg} alt="Masteriyo Logo" />
					</LogoContainer>
					<Menu>
						<MenuItem>Course</MenuItem>
						<MenuItem>Course Builder</MenuItem>
						<MenuItem icon={<BiShow />}>Settings</MenuItem>
					</Menu>
				</HeaderLeftContent>
				<HeaderRightContent>
					<HeaderActions>
						<Button type="default" icon={<BiShow />}>
							Preview
						</Button>
						<Button type="primary">Save</Button>
					</HeaderActions>
				</HeaderRightContent>
			</ContainerFluid>
		</Header>
	);
};

export default MainToolbar;

const Header = styled.header`
	background-color: ${colors.WHITE};
	border-bottom: 1px solid ${colors.BORDER};
	height: 64px;
`;

const LogoContainer = styled(Flex)`
	max-width: 100px;

	img {
		max-width: 100%;
	}
`;
const HeaderLeftContent = styled(FlexRow)``;

const HeaderRightContent = styled(FlexRow)`
	margin-left: auto;
	justify-content: flex-end;
`;

const HeaderActions = styled(FlexRow)`
	button {
		margin-left: ${BaseLine * 2}px;
	}
`;
