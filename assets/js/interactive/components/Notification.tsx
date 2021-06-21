import {
	Popover,
	PopoverTrigger,
	PopoverContent,
	PopoverHeader,
	PopoverBody,
	PopoverFooter,
	PopoverArrow,
	Icon,
	Button,
	ButtonGroup,
} from '@chakra-ui/react';
import React from 'react';
import { __ } from '@wordpress/i18n';
import { BiBell } from 'react-icons/bi';

const Notification = () => {
	return (
		<Popover placement="bottom">
			<PopoverTrigger>
				<Button variant="unstyled" fontSize="l">
					<Icon as={BiBell} />
				</Button>
			</PopoverTrigger>
			<PopoverContent>
				<PopoverArrow />
				<PopoverHeader>Confirmation!</PopoverHeader>
				<PopoverBody>Are you sure you want to have that milkshake?</PopoverBody>
				<PopoverFooter>
					<ButtonGroup>
						<Button variant="link">
							{__('Mark all as read', 'masteriyo')}
						</Button>
						<Button variant="link">{__('Clear All', 'masteriyo')}</Button>
					</ButtonGroup>
				</PopoverFooter>
			</PopoverContent>
		</Popover>
	);
};

export default Notification;
