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
import { useQuery } from 'react-query';
import { NavLink, useLocation } from 'react-router-dom';
import FullScreenLoader from '../../back-end/components/layout/FullScreenLoader';
import urls from '../../back-end/constants/urls';
import { UserSchema } from '../../back-end/schemas';
import API from '../../back-end/utils/api';
import routes from '../constants/routes';

const Sidebar = () => {
	const location = useLocation();
	const userAPI = new API(urls.currentUser);

	const { data, isSuccess } = useQuery<UserSchema>('userProfile', () =>
		userAPI.get()
	);

	const navLinkStyles = {
		borderRight: '2px',
		d: 'block',
		borderColor: 'transparent',
		color: 'gray.600',
		fontWeight: 'medium',
		textDecoration: 'none',
		_hover: {
			color: 'blue.500',
		},
	};
	const navActiveStyles = {
		borderColor: 'blue.500',
		color: 'blue.500',
	};
	if (isSuccess) {
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
							name={data?.first_name}
							src={data?.avatar_url}
							showBorder
							shadow="md"
						/>
						<Stack direction="column" spacing="0">
							<Heading as="h5" fontSize="xs" fontWeight="medium">
								{data?.first_name} {data?.last_name}
							</Heading>
						</Stack>
					</Stack>
				</ListItem>
				<ListItem>
					<Link
						as={NavLink}
						sx={navLinkStyles}
						_activeLink={navActiveStyles}
						to={routes.dashboard}>
						<ListIcon fontSize="md" mr="3" as={BiGrid} />
						{__('Dashboard', 'masteriyo')}
					</Link>
				</ListItem>
				<ListItem>
					<Link
						as={NavLink}
						sx={navLinkStyles}
						_activeLink={navActiveStyles}
						to={routes.myCourses}>
						<ListIcon fontSize="md" mr="3" as={BiBook} />
						{__('My Courses')}
					</Link>
				</ListItem>
				<ListItem>
					<Link
						as={NavLink}
						sx={
							location.pathname?.includes('/user')
								? navActiveStyles
								: navLinkStyles
						}
						_activeLink={navActiveStyles}
						to={routes.user.profile}>
						<ListIcon fontSize="md" mr="3" as={BiUser} />
						{__('My Profile')}
					</Link>
				</ListItem>

				<ListItem>
					<Link
						as={NavLink}
						sx={navLinkStyles}
						_activeLink={navActiveStyles}
						to={routes.myOrderHistory}>
						<ListIcon fontSize="md" mr="3" as={BiHistory} />
						{__('My Order History')}
					</Link>
				</ListItem>

				<ListItem>
					<ListIcon fontSize="md" mr="3" as={BiLogOut} />
					{__('Logout')}
				</ListItem>
			</List>
		);
	}

	return <FullScreenLoader />;
};

export default Sidebar;
