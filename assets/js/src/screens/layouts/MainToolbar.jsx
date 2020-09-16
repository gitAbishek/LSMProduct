import { React } from '@wordpress/element';
import colors from './../../config/colors';
import styled from 'styled-components';
import Flex from './../../components/common/Flex';
import LogoImg from '../../../../img/logo.png';
import Button from './../../components/common/Button';
import FlexRow from '../../components/common/FlexRow';
import Icon from '../../components/common/Icon';
import { ContainerFluid } from '../../components/common/Container';
import { BaseLine } from '../../config/defaultStyle';
import { BiShow } from 'react-icons/bi';
import Menu, { MenuItem } from 'rc-menu';
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
						<MenuItem>Settings</MenuItem>
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

const HeaderActions = styled(FlexRow)`
	button {
		margin-left: ${BaseLine * 2}px;
	}
`;
