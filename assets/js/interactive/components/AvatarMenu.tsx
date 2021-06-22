import {
	Avatar,
	Menu,
	MenuButton,
	MenuList,
	MenuItem,
	MenuDivider,
} from '@chakra-ui/react';
import React from 'react';
import { __ } from '@wordpress/i18n';

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
