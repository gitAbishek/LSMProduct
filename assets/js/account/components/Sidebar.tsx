import {
	Avatar,
	Heading,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiBook, BiGrid, BiHistory, BiLogOut, BiUser } from 'react-icons/bi';
import { NavLink } from 'react-router-dom';
import routes from '../constants/routes';

const Sidebar = () => {
	const navLinkStyles = {
		borderRight: '2px',
		d: 'block',
		borderColor: 'transparent',
	};
	const navActiveStyles = {
		borderColor: 'blue.500',
		color: 'blue.500',
	};
	return (
		<List
			mr="6"
			flex="0 0 200px"
			borderRight="1px"
			borderRightColor="gray.100"
			spacing="6"
			fontSize="sm"
			fontWeight="medium"
			color="gray.600">
			<ListItem>
				<Stack direction="row" align="center">
					<Avatar size="sm" showBorder />
					<Heading fontSize="sm">John Doe</Heading>
				</Stack>
			</ListItem>
			<ListItem>
				<Link
					as={NavLink}
					sx={navLinkStyles}
					_activeLink={navActiveStyles}
					to={routes.dashboard}>
					<ListIcon as={BiGrid} />
					{__('Dashboard', 'masteriyo')}
				</Link>
			</ListItem>
			<ListItem>
				<Link
					as={NavLink}
					sx={navLinkStyles}
					_activeLink={navActiveStyles}
					to={routes.myCourses}>
					<ListIcon as={BiBook} />
					{__('My Courses')}
				</Link>
			</ListItem>
			<ListItem>
				<Link
					as={NavLink}
					sx={navLinkStyles}
					_activeLink={navActiveStyles}
					to={routes.myProfile}>
					<ListIcon as={BiUser} />
					{__('My Profile')}
				</Link>
			</ListItem>

			<ListItem>
				<Link
					as={NavLink}
					sx={navLinkStyles}
					_activeLink={navActiveStyles}
					to={routes.myOrderHistory}>
					<ListIcon as={BiHistory} />
					{__('My Order History')}
				</Link>
			</ListItem>

			<ListItem>
				<ListIcon as={BiLogOut} />
				{__('Logout')}
			</ListItem>
		</List>
	);
};

export default Sidebar;
