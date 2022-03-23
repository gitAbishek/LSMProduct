import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Avatar,
	Button,
	ButtonGroup,
	Divider,
	Heading,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import {
	BiBook,
	BiBookAlt,
	BiGrid,
	BiHistory,
	BiLogOut,
	BiPlus,
	BiUser,
} from 'react-icons/bi';
import { useQuery } from 'react-query';
import { NavLink, useLocation } from 'react-router-dom';
import FullScreenLoader from '../../back-end/components/layout/FullScreenLoader';
import urls from '../../back-end/constants/urls';
import { UserSchema } from '../../back-end/schemas';
import API from '../../back-end/utils/api';
import localized from '../../back-end/utils/global';
import routes from '../constants/routes';

const Sidebar = () => {
	const location = useLocation();
	const userAPI = new API(urls.currentUser);
	const userLogoutAPI = new API(urls.userLogout);
	const userQuery = useQuery<UserSchema>('userProfile', () => userAPI.get());
	const { onClose, onOpen, isOpen } = useDisclosure();
	const [isLogoutLoading, setIsLogoutLoading] = useState(false);
	const cancelRef = React.useRef<any>();
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

	const redirectLogoutUser = () => {
		setIsLogoutLoading(true);
		userLogoutAPI.get().then((data: { redirect_url: string }) => {
			setIsLogoutLoading(false);
			window.location.href = data?.redirect_url;
		});
	};

	if (userQuery.isSuccess) {
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
							name={userQuery?.data?.first_name}
							src={userQuery?.data?.avatar_url}
							showBorder
							shadow="md"
						/>
						<Stack direction="column" spacing="0">
							<Heading as="h5" fontSize="xs" fontWeight="medium">
								{userQuery?.data?.first_name && userQuery?.data?.last_name
									? `${userQuery?.data?.first_name} ${userQuery?.data?.last_name}`
									: userQuery?.data?.username}
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

				{userQuery?.data?.roles.includes('masteriyo_instructor') ? (
					<>
						<Divider />
						<ListItem>
							<Link sx={navLinkStyles} href={localized.urls.addNewCourse}>
								<ListIcon fontSize="md" mr="3" as={BiPlus} />
								{__('Add course', 'masteriyo')}
							</Link>
						</ListItem>
						<ListItem>
							<Link sx={navLinkStyles} href={localized.urls.myCourses}>
								<ListIcon fontSize="md" mr="3" as={BiBookAlt} />
								{__('My Courses', 'masteriyo')}
							</Link>
						</ListItem>
						<Divider />
					</>
				) : null}
				<ListItem>
					<Link
						as={NavLink}
						sx={navLinkStyles}
						_activeLink={navActiveStyles}
						to={routes.courses}>
						<ListIcon fontSize="md" mr="3" as={BiBook} />
						{__('Enrolled Courses', 'masteriyo')}
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
						{__('Profile', 'masteriyo')}
					</Link>
				</ListItem>

				<ListItem>
					<Link
						as={NavLink}
						sx={navLinkStyles}
						_activeLink={navActiveStyles}
						to={routes.order.list}>
						<ListIcon fontSize="md" mr="3" as={BiHistory} />
						{__('Order History', 'masteriyo')}
					</Link>
				</ListItem>

				<ListItem>
					<Link sx={navLinkStyles} onClick={onOpen}>
						<ListIcon fontSize="md" mr="3" as={BiLogOut} />
						{__('Logout', 'masteriyo')}
					</Link>
				</ListItem>

				<AlertDialog
					isOpen={isOpen}
					onClose={onClose}
					isCentered
					leastDestructiveRef={cancelRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Log Out', 'masteriyo')} {name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__('Do you really want to logout?', 'masteriyo')}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button onClick={onClose} variant="outline" ref={cancelRef}>
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										onClick={redirectLogoutUser}
										isLoading={isLogoutLoading}>
										{__('Logout', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			</List>
		);
	}

	return <FullScreenLoader />;
};

export default Sidebar;
