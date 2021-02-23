import { Book, Cog, Edit, Show } from '../../assets/icons';
import Menu, { MenuItem } from 'Components/common/Menu';

import { BaseLine } from 'Config/defaultStyle';
import Button from 'Components/common/Button';
import { ContainerFluid } from 'Components/common/Container';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import LogoImg from '../../../../img/logo.png';
import React from 'react';
import colors from 'Config/colors';
import styled from 'styled-components';
import { __ } from '@wordpress/i18n';

const MainToolbar = () => {
	return (
		<Header>
			<ContainerFluid flex>
				<HeaderLeftContent>
					<LogoContainer>
						<img src={LogoImg} alt="Masteriyo Logo" />
					</LogoContainer>
					<Menu>
						<MenuItem to="/courses" icon={<Book />}>
							{__( 'Courses', 'masteriyo' )}
						</MenuItem>
						<MenuItem to="/courses/add-new-course" icon={<Edit />}>
							{__( 'Course Builder', 'masteriyo' )}
						</MenuItem>
						<MenuItem to="/settings" icon={<Cog />}>
							{__( 'Settings', 'masteriyo' )}
						</MenuItem>
					</Menu>
				</HeaderLeftContent>
				<HeaderRightContent>
					<HeaderActions>
						<Button icon={<Show />}>{__( 'Preview', 'masteriyo' )}</Button>
						<Button appearance="primary">{__( 'Save', 'masteriyo' )}</Button>
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
