import { Book, Cog, Edit, Show } from 'Icons';
import Menu, { MenuItem } from 'Components/common/Menu';

import { BaseLine } from 'Config/defaultStyle';
import Button from 'Components/common/Button';
import { ContainerFluid } from 'Components/common/Container';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import LogoImg from '../../../../img/logo.png';
import { React } from '@wordpress/element';
import colors from 'Config/colors';
import styled from 'styled-components';

const MainToolbar = () => {
	return (
		<Header>
			<ContainerFluid flex>
				<HeaderLeftContent>
					<LogoContainer>
						<img src={LogoImg} alt="Masteriyo Logo" />
					</LogoContainer>
					<Menu>
						<MenuItem to="/course" icon={<Book />}>
							Course
						</MenuItem>
						<MenuItem to="/builder" icon={<Edit />}>
							Course Builder
						</MenuItem>
						<MenuItem to="/settings" icon={<Cog />}>
							Settings
						</MenuItem>
					</Menu>
				</HeaderLeftContent>
				<HeaderRightContent>
					<HeaderActions>
						<Button icon={<Show />}>Preview</Button>
						<Button primary>Save</Button>
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
	height: ${BaseLine * 8}px;
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
