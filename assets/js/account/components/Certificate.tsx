import {
	Box,
	Button,
	Flex,
	Image,
	Spacer,
	Stack,
	Text,
} from '@chakra-ui/react';
import React from 'react';
import { default as Book } from '../../../img/Rectangle.png';

interface Props {
	id: number;
	title: string;
}

const Certificate: React.FC<Props> = ({ id, title }) => {
	return (
		<Flex px={2}>
			<Box p="4">
				<Stack direction="row" spacing={3} px={5}>
					<Image src={Book} alt="Book" />
					<Text fontWeight={'semibold'}>{title}</Text>
				</Stack>
			</Box>
			<Spacer />
			<Box p="4">
				<Button
					fontSize={'sm'}
					rounded={'full'}
					bg={'blue.600'}
					color={'white'}
					_hover={{
						bg: 'blue.500',
					}}
					_focus={{
						bg: 'blue.500',
					}}>
					Download
				</Button>
			</Box>
		</Flex>
	);
};

export default Certificate;
