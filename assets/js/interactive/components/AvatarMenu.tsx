import {
	Avatar,
	Link,
	Menu,
	MenuButton,
	MenuDivider,
	MenuItem,
	MenuList,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiLogOut, BiUserCircle } from 'react-icons/bi';
import localized from '../utils/global';

const AvatarMenu = () => {
	return (
		<Menu placement="bottom-end">
			<MenuButton>
				<Avatar src={localized.userAvatar} size="sm" />
			</MenuButton>
			<MenuList fontSize="sm" textAlign="center">
				<Link color="gray.500" href={localized.urls.account} isExternal>
					<MenuItem icon={<BiUserCircle size={22} />}>
						{__('My Profile', 'masteriyo')}
					</MenuItem>
				</Link>
				<MenuDivider />
				<Link color="gray.500" href={localized.urls.logout} isExternal>
					<MenuItem icon={<BiLogOut size={22} />}>
						{__('Log Out', 'masteriyo')}
					</MenuItem>
				</Link>
			</MenuList>
		</Menu>
	);
};

export default AvatarMenu;
