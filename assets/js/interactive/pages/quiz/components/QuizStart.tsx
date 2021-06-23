import {
	Button,
	ButtonGroup,
	Icon,
	List,
	ListIcon,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiCheckCircle,
	BiCheckDouble,
	BiChevronRight,
	BiInfoCircle,
	BiTime,
} from 'react-icons/bi';

const QuizStart = () => {
	const listItemStyles = {
		d: 'flex',
		alignItems: 'center',
		borderRight: '1px',
		borderRightColor: 'rgba(255, 255, 255, 0.2)',
		px: '3',
		'.chakra-icon': {
			fontSize: 'lg',
		},
		_last: {
			borderRightColor: 'transparent',
		},
	};
	return (
		<Stack direction="column" spacing="8">
			<List
				bg="blue.500"
				rounded="sm"
				d="flex"
				flexDirection="row"
				alignItems="center"
				py="3"
				color="white"
				fontSize="xs">
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiTime} />
					<Text as="strong">{__('Duration: ')}</Text>
					<Text ml="1">1h 35m</Text>
				</ListItem>
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiInfoCircle} />
					<Text as="strong">{__('Questions: ')}</Text>
					<Text ml="1">5</Text>
				</ListItem>
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiCheckCircle} />
					<Text as="strong">{__('Mark: ')}</Text>
					<Text ml="1">100</Text>
				</ListItem>
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiCheckDouble} />
					<Text as="strong">{__('Pass Mark: ')}</Text>
					<Text ml="1">40</Text>
				</ListItem>
			</List>

			<ButtonGroup>
				<Button
					type="submit"
					colorScheme="blue"
					rounded="full"
					fontWeight="bold"
					rightIcon={<Icon as={BiChevronRight} fontSize="x-large" />}
					textTransform="uppercase">
					{__('Start Quiz', 'masteriyo')}
				</Button>
			</ButtonGroup>
		</Stack>
	);
};

export default QuizStart;
