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
	const onMenuItemClick = (path: string) => (window.location.href = `${path}`);

	return (
		<Menu placement="bottom-end">
			<MenuButton>
				<Avatar size="sm" />
			</MenuButton>
			<MenuList fontSize="sm">
				<MenuItem
					onClick={() =>
						onMenuItemClick(
							//@ts-ignore
							`${_MASTERIYO_.urls.myaccount}`
						)
					}>
					{__('My Profile', 'masteriyo')}
				</MenuItem>
				<MenuDivider />
				<MenuItem
					onClick={() =>
						onMenuItemClick(
							//@ts-ignore
							`${_MASTERIYO_.urls.logout}`
						)
					}>
					{__('Log Out', 'masteriyo')}
				</MenuItem>
			</MenuList>
		</Menu>
	);
};

export default AvatarMenu;
