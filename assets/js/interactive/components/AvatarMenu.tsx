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
				<Link
					color="gray.500"
					href={
						//@ts-ignore
						`${_MASTERIYO_.urls.myaccount}`
					}
					isExternal>
					<MenuItem icon={<BiUserCircle size={22} />}>
						{__('My Profile', 'masteriyo')}
					</MenuItem>
				</Link>
				<MenuDivider />
				<Link
					color="gray.500"
					href={
						//@ts-ignore
						`${_MASTERIYO_.urls.logout}`
					}
					isExternal>
					<MenuItem icon={<BiLogOut size={22} />}>
						{__('Log Out', 'masteriyo')}
					</MenuItem>
				</Link>
			</MenuList>
		</Menu>
	);
};

export default AvatarMenu;
