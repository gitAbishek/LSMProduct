import {
	Popover,
	PopoverTrigger,
	PopoverContent,
	PopoverHeader,
	PopoverBody,
	PopoverFooter,
	PopoverArrow,
	Icon,
} from '@chakra-ui/react';
import React from 'react';
import { __ } from '@wordpress/i18n';
import { BiBell } from 'react-icons/bi';

const Notification = () => {
	return (
		<Popover>
			<PopoverTrigger>
				<Icon as={BiBell} />
			</PopoverTrigger>
			<PopoverContent>
				<PopoverArrow />
				<PopoverHeader>Confirmation!</PopoverHeader>
				<PopoverBody>Are you sure you want to have that milkshake?</PopoverBody>
			</PopoverContent>
		</Popover>
	);
};

export default Notification;
