import { Book, Cog, Edit, Show } from '../../assets/icons';
import Menu, { MenuItem } from 'Components/common/Menu';

import { BaseLine } from 'Config/defaultStyle';
import Button from 'Components/common/Button';
import Container from 'Components/common/Container';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import LogoImg from '../../../../img/logo.png';
import React from 'react';
import { __ } from '@wordpress/i18n';
import colors from 'Config/colors';
import styled from 'styled-components';

const MainToolbar = () => {
	return (
		<header className="mto-bg-white mto-shadow-sm">
			<div className="mto-container mto-mx-auto mto-flex mto-justify-between mto-items-center">
				<div className="mto-flex mto-items-center">
					<div>
						<img src={LogoImg} alt="Masteriyo Logo" />
					</div>
					<Menu>
						<MenuItem to="/courses" icon={<Book />}>
							{__('Courses', 'masteriyo')}
						</MenuItem>
						<MenuItem to="/courses/add-new-course" icon={<Edit />}>
							{__('Course Builder', 'masteriyo')}
						</MenuItem>
						<MenuItem to="/settings" icon={<Cog />}>
							{__('Settings', 'masteriyo')}
						</MenuItem>
					</Menu>
				</div>
				<div>
					<div className="mto-flex">
						<Button icon={<Show />}>{__('Preview', 'masteriyo')}</Button>
						<Button layout="primary" className="mto-ml-4">
							{__('Save', 'masteriyo')}
						</Button>
					</div>
				</div>
			</div>
		</header>
	);
};

export default MainToolbar;
