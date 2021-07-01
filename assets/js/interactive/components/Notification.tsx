import {
	Avatar,
	Button,
	ButtonGroup,
	Heading,
	IconButton,
	List,
	ListItem,
	Popover,
	PopoverArrow,
	PopoverBody,
	PopoverContent,
	PopoverFooter,
	PopoverHeader,
	PopoverTrigger,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiBell } from 'react-icons/bi';

const Notification = () => {
	const listItemStyles = {
		d: 'flex',
		justifyContent: 'space-between',
		borderBottom: '1px',
		borderColor: 'gray.100',
		pb: '2',
		_last: {
			borderColor: 'transparent',
		},
	};

	return (
		<Popover placement="bottom">
			<PopoverTrigger>
				<IconButton
					variant="unstyled"
					fontSize="md"
					icon={<BiBell />}
					aria-label={__('Open Notification Panel', 'masteriyo')}
				/>
			</PopoverTrigger>
			<PopoverContent>
				<PopoverArrow />
				<PopoverHeader>{__('Notification', 'masteriyo')}</PopoverHeader>
				<PopoverBody>
					<List spacing="2">
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
						<Button variant="link" _hover={{ color: 'blue.500' }}>
							{__('Mark all as read', 'masteriyo')}
						</Button>
						<Button variant="link" _hover={{ color: 'blue.500' }}>
							{__('Clear All', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</PopoverFooter>
			</PopoverContent>
		</Popover>
	);
};

export default Notification;
