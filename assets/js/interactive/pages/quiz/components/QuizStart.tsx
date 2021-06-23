import { Box, List, ListIcon, ListItem, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiInfoCircle, BiTime } from 'react-icons/bi';

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
		<Box>
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
			</List>
		</Box>
	);
};

export default QuizStart;
