import { Avatar, Box, Icon, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { BiStar, BiUser } from 'react-icons/bi';

interface Props {
	message: string;
	avatarUrl: string;
	name: string;
	time: string;
	sender: 'user' | 'author';
}
const Message: React.FC<Props> = (props) => {
	const { message, avatarUrl, name, time, sender } = props;

	return (
		<Stack direction="row" spacing="2" justify="space-between">
			<Avatar size="sm" src="https://i.pravatar.cc/150?img=3" />
			<Stack direction="column" spacing="2" flex="1">
				<Stack
					direction="row"
					spacing="1"
					align="center"
					justify="space-between">
					<Stack direction="row" align="center">
						<Text fontSize="sm" fontWeight="medium">
							{name}
						</Text>
						<Icon as={sender === 'user' ? BiUser : BiStar} color="pink.400" />
					</Stack>
					<Text fontSize="x-small" color="gray.400">
						{time}
					</Text>
				</Stack>
				<Box
					fontSize="xs"
					bg="gray.100"
					p="4"
					borderBottomRightRadius="lg"
					borderBottomLeftRadius="lg"
					borderTopRightRadius="lg">
					What is the Screencast videos?
				</Box>
			</Stack>
		</Stack>
	);
};

export default Message;
