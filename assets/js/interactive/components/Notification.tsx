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
	Stack,
	Avatar,
	Heading,
	Text,
	List,
	ListItem,
} from '@chakra-ui/react';
import React from 'react';
import { __ } from '@wordpress/i18n';
import { BiBell } from 'react-icons/bi';

const Notification = () => {
	const listItemStyles = {
		d: 'flex',
		justifyContent: 'space-between',
	};

	return (
		<Popover placement="bottom">
			<PopoverTrigger>
				<Button variant="unstyled" fontSize="l">
					<Icon as={BiBell} />
				</Button>
			</PopoverTrigger>
			<PopoverContent>
				<PopoverArrow />
				<PopoverHeader>{__('Notification', 'masteriyo')}</PopoverHeader>
				<PopoverBody>
					<List>
						<ListItem sx={listItemStyles}>
							<Stack direction="row" spacing="2" maxW="240px">
								<Avatar size="sm" />
								<Stack direction="column" spacing="1">
									<Heading as="h5" fontSize="sm">
										Sarah Jones
									</Heading>
									<Text fontSize="xs">
										Asked a question in{' '}
										<Text as="strong">How to build WordPress website</Text>
									</Text>
								</Stack>
							</Stack>
							<Text fontSize="xs" color="gray.300" ml="4">
								2 min Ago
							</Text>
						</ListItem>
					</List>
				</PopoverBody>
				<PopoverFooter>
					<ButtonGroup justifyContent="flex-end" d="flex">
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
