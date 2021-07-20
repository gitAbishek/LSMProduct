import {
	Avatar,
	Menu,
	MenuButton,
	MenuDivider,
	MenuItem,
	MenuList,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

const AvatarMenu = () => {
	return (
		<Menu placement="bottom-end">
			<MenuButton>
				<Avatar size="sm" />
			</MenuButton>
			<MenuList fontSize="sm">
				<MenuItem>{__('My Profile', 'masteriyo')}</MenuItem>
				<MenuItem>{__('Settings', 'masteriyo')}</MenuItem>
				<MenuItem>{__('My Wishlist', 'masteriyo')}</MenuItem>
				<MenuDivider />
				<MenuItem>{__('Log Out', 'masteriyo')}</MenuItem>
			</MenuList>
		</Menu>
	);
};

export default AvatarMenu;
