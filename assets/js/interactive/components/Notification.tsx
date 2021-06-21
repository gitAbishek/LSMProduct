import {
	Avatar,
	Menu,
	MenuButton,
	MenuList,
	MenuItem,
	MenuDivider,
	Icon,
} from '@chakra-ui/react';
import React from 'react';
import { __ } from '@wordpress/i18n';
import { BiBell } from 'react-icons/bi';

const Notification = () => {
	return (
		<Menu placement="bottom-end">
			<MenuButton>
				<Icon as={BiBell} />
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

export default Notification;
