import { Avatar, Box, Icon, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { BiBadgeCheck, BiUser } from 'react-icons/bi';

interface Props {
	message: string;
	avatar: string;
	name: string;
	time: string;
	sender: 'user' | 'author';
}
const Message: React.FC<Props> = (props) => {
	const { message, avatar, name, time, sender = 'user' } = props;

	const bubbleStyle =
		sender === 'user'
			? {
					borderBottomRightRadius: 'lg',
					borderBottomLeftRadius: 'lg',
					borderTopRightRadius: 'lg',
					bg: 'gray.100',
			  }
			: {
					borderBottomRightRadius: 'lg',
					borderBottomLeftRadius: 'lg',
					borderTopLeftRadius: 'lg',
					bg: 'blue.400',
					color: 'white',
			  };
	return (
		<Stack
			direction={sender === 'user' ? 'row' : 'row-reverse'}
			spacing="2"
			justify="space-between">
			<Avatar size="sm" src={avatar} />
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
						<Icon
							as={sender === 'user' ? BiUser : BiBadgeCheck}
							color={sender === 'user' ? 'pink.400' : 'blue.400'}
						/>
					</Stack>
					<Text fontSize="x-small" color="gray.400">
						{time}
					</Text>
				</Stack>
				<Box fontSize="xs" p="4" sx={bubbleStyle}>
					{message}
				</Box>
			</Stack>
		</Stack>
	);
};

export default Message;
