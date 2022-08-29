import {
	Button,
	Icon,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { ReactElement } from 'react';
import { FaUserFriends, FaUserTie } from 'react-icons/fa';
import { NavLink, useHistory, useLocation } from 'react-router-dom';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';

interface PropsType {
	thirdBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
		icon?: ReactElement;
	};
}

const UserHeader: React.FC<PropsType> = (props) => {
	const location = useLocation();
	const history = useHistory();

	const userStyleButton = {
		mr: '10',
		py: '6',
		d: 'flex',
		gap: 1,
		justifyContent: 'flex-start',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: ['xs', null, 'sm'],
	};

	return (
		<Header showLinks thirdBtn={props.thirdBtn}>
			<List d={['none', 'none', 'flex']}>
				<ListItem mb="0">
					<Link
						as={NavLink}
						sx={navLinkStyles}
						_activeLink={navActiveStyles}
						to={routes.users.students.list}>
						<ListIcon as={FaUserFriends} />
						{__('Students', 'masteriyo')}
					</Link>
				</ListItem>

				<ListItem mb="0">
					<Link
						as={NavLink}
						sx={navLinkStyles}
						isActive={() => location.pathname.includes('/instructors')}
						_activeLink={navActiveStyles}
						to={routes.users.instructors.list}>
						<ListIcon as={FaUserTie} />
						{__('Instructors', 'masteriyo')}
					</Link>
				</ListItem>
			</List>
			<Stack direction="column" display={{ md: 'none' }}>
				<Stack>
					<Button
						color="gray.600"
						variant="link"
						sx={userStyleButton}
						isActive={location.pathname.includes('/students')}
						_active={navActiveStyles}
						rounded="none"
						_hover={{ color: 'primary.500' }}
						onClick={() => history.push(routes.users.students.list)}>
						<Icon as={FaUserFriends} />
						{__('Students', 'masteriyo')}
					</Button>
				</Stack>
				<Stack>
					<Button
						color="gray.600"
						variant="link"
						sx={userStyleButton}
						_active={navActiveStyles}
						isActive={location.pathname.includes('/instructors')}
						rounded="none"
						_hover={{ color: 'primary.500' }}
						onClick={() => history.push(routes.users.instructors.list)}>
						<Icon as={FaUserTie} />
						{__('Instructors', 'masteriyo')}
					</Button>
				</Stack>
			</Stack>
		</Header>
	);
};

export default UserHeader;
