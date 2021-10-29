import { Button, Image, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { default as Book } from '../../../img/Rectangle.png';

interface Props {
	id: number;
	title: string;
}

const Certificate: React.FC<Props> = ({ id, title }) => {
	return (
		<Stack direction="row" justify="space-between">
			<Stack direction="row" spacing={3}>
				<Image src={Book} alt="Book" />
				<Text fontWeight={'semibold'}>{title}</Text>
			</Stack>

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
		</Stack>
	);
};

export default Certificate;
