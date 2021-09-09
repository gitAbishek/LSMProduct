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

//@ts-ignore
const userAvatar = _MASTERIYO_.userAvatar;

const AvatarMenu = () => {
	return (
		<Menu placement="bottom-end">
			<MenuButton>
				<Avatar src={userAvatar} size="sm" />
			</MenuButton>
			<MenuList fontSize="sm" textAlign="center">
				<MenuItem icon={<BiUserCircle size={22} />}>
					<Link
						color="gray.500"
						href={
							//@ts-ignore
							`${_MASTERIYO_.urls.myaccount}`
						}
						isExternal>
						{__('My Profile', 'masteriyo')}
					</Link>
				</MenuItem>
				<MenuDivider />
				<MenuItem icon={<BiLogOut size={22} />}>
					<Link
						color="gray.500"
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
