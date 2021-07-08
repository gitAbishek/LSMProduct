import { Avatar, Box, Icon, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { BiBadgeCheck, BiUser } from 'react-icons/bi';
import Timeago from 'react-timeago';

interface Props {
	message: string;
	avatar: string;
	name: string;
	time: string;
	sender: 'student' | 'instructor';
	byCurrentUser: boolean;
}
const Message: React.FC<Props> = (props) => {
	const { message, avatar, name, time, sender, byCurrentUser } = props;

	const bubbleStyle = byCurrentUser
		? {
				borderBottomRightRadius: 'lg',
				borderBottomLeftRadius: 'lg',
				borderTopLeftRadius: 'lg',
				bg: 'blue.400',
				color: 'white',
		  }
		: {
				borderBottomRightRadius: 'lg',
				borderBottomLeftRadius: 'lg',
				borderTopRightRadius: 'lg',
				bg: 'gray.100',
		  };

	return (
		<Stack
			direction={byCurrentUser ? 'row-reverse' : 'row'}
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
							as={sender === 'instructor' ? BiBadgeCheck : BiUser}
							color={sender === 'instructor' ? 'blue.400' : 'pink.400'}
						/>
					</Stack>
					<Text fontSize="x-small" color="gray.400">
						<Timeago date={time} />
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
