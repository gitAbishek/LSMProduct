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

const AvatarMenu = () => {
	return (
		<Menu placement="bottom-end">
			<MenuButton>
				<Avatar size="sm" />
			</MenuButton>
			<MenuList fontSize="sm">
				<MenuItem>
					<Link
						href={
							//@ts-ignore
							`${_MASTERIYO_.urls.myaccount}`
						}
						isExternal>
						{__('My Profile', 'masteriyo')}
					</Link>
				</MenuItem>
				<MenuDivider />
				<MenuItem>
					<Link
						href={
							//@ts-ignore
							`${_MASTERIYO_.urls.logout}`
						}
						isExternal>
						{__('Log Out', 'masteriyo')}
					</Link>
				</MenuItem>
			</MenuList>
		</Menu>
	);
};

export default AvatarMenu;
