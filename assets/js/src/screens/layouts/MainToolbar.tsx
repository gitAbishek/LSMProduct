import { Box, Container, Flex, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Button from 'Components/common/Button';
import Icon from 'Components/common/Icon';
import React from 'react';
import { NavLink } from 'react-router-dom';

import LogoImg from '../../../../img/logo.png';
import { Book, Cog, Edit, Show } from '../../assets/icons';

const MainToolbar = () => {
	return (
		<Box bg="white">
			<Container maxW="container.xl">
				<Stack direction="column">
					<div>
						<img src={LogoImg} alt="Masteriyo Logo" />
					</div>
					<ul className="mto-flex mto-ml-10">
						<li className="mto-mb-0 mto-text-base">
							<NavLink
								to="/courses"
								className="mto-flex mto-items-center mto-ml-12 mto-py-7 mto-font-medium mto-nav-link">
								<Icon className="mto-mr-1" icon={<Book />} />
								{__('Courses', 'masteriyo')}
							</NavLink>
						</li>
						<li className="mto-mb-0 mto-text-base">
							<NavLink
								to="/builder"
								className="mto-flex mto-items-center mto-ml-12 mto-py-7 mto-font-medium mto-nav-link">
								<Icon className="mto-mr-1" icon={<Edit />} />
								{__('Course Builder', 'masteriyo')}
							</NavLink>
						</li>
						<li className="mto-mb-0 mto-text-base">
							<NavLink
								to="/settings"
								className="mto-flex mto-items-center mto-ml-12 mto-py-7 mto-font-medium mto-nav-link">
								<Icon className="mto-mr-1" icon={<Cog />} />
								{__('Settings', 'masteriyo')}
							</NavLink>
						</li>
					</ul>

					<div>
						<div className="mto-flex">
							<Button icon={<Show />}>{__('Preview', 'masteriyo')}</Button>
							<Button layout="primary" className="mto-ml-4">
								{__('Save', 'masteriyo')}
							</Button>
						</div>
					</div>
				</Stack>
			</Container>
		</Box>
	);
};

export default MainToolbar;
