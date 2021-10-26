import { DragHandleIcon } from '@chakra-ui/icons';
import {
	Avatar,
	Box,
	Center,
	Container,
	Flex,
	Stack,
	Text,
} from '@chakra-ui/react';
import React from 'react';
import Courses from './courses/Courses';

const Dashboard = () => {
	return (
		<Flex direction="row">
			<Container maxW="sm" borderColor={'gray.200'} borderRadius="1">
				<Stack direction="row" spacing={4} mt={10} px={10}>
					<Avatar name="Dan Abrahmov" src="https://bit.ly/dan-abramov" />
					<Text>
						Jamie Oliver <br />
						Gold Member
					</Text>
					<DragHandleIcon />
				</Stack>

				<Stack direction="column" mt={10} px={10}>
					<Box background="blue.300" mt={5}>
						<Center>Dashboard</Center>
					</Box>
					<Box mt={5}>
						<Center>Dashboard</Center>
					</Box>
					<Box mt={5}>
						<Center>Dashboard</Center>
					</Box>
					<Box mt={5}>
						<Center>Dashboard</Center>
					</Box>
					<Box mt={5}>
						<Center>Dashboard</Center>
					</Box>
				</Stack>
			</Container>
			<Courses />
		</Flex>
	);
};

export default Dashboard;
