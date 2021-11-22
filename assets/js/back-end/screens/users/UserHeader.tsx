import { Link, List, ListIcon, ListItem } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FaUserFriends, FaUserTie } from 'react-icons/fa';
import { NavLink, useLocation } from 'react-router-dom';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';

const UserHeader: React.FC = () => {
	const location = useLocation();
	return (
		<Header showLinks>
			<List d="flex">
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
		</Header>
	);
};

export default UserHeader;
