import {
	Avatar,
	Heading,
	Icon,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiBook,
	BiDotsVerticalRounded,
	BiGrid,
	BiHistory,
	BiLogOut,
	BiUser,
} from 'react-icons/bi';
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
				<Stack direction="row" align="center" spacing="3">
					<Avatar
						size="sm"
						name="Jamie Oliver"
						src="https://bit.ly/sage-adebayo"
						showBorder
						shadow="md"
					/>
					<Stack direction="column" spacing="0">
						<Heading as="h5" fontSize="sm" fontWeight="medium">
							{__('Jamie Oliver', 'masteriyo')}
						</Heading>
						<Text color="gray.300" fontSize="xx-small" fontWeight="medium">
							{__('Gold Member', 'masteriyo')}
						</Text>
					</Stack>
					<Icon as={BiDotsVerticalRounded} w={6} h={6} color="gray.300" />
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
