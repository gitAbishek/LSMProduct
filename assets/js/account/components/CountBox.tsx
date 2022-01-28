import {
	Box,
	Center,
	Heading,
	Icon,
	Stack,
	Text,
	ThemingProps,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { HiOutlineAcademicCap } from 'react-icons/hi';

interface Props {
	count: number;
	title: string;
	colorScheme: ThemingProps['colorScheme'];
}

const CountBox: React.FC<Props> = (props) => {
	const { title, count, colorScheme } = props;

	return (
		<Box w="xs" borderWidth="1px" borderColor="gray.100">
			<Stack direction="row" spacing="4" p="6">
				<Stack
					direction="row"
					spacing="4"
					align="center"
					justify="space-between">
					<Center
						bg={`${colorScheme}.100`}
						w="16"
						h="16"
						rounded="xl"
						color={`${colorScheme}.500`}
						fontSize="3xl">
						<Icon as={HiOutlineAcademicCap} />
					</Center>
					<Stack direction="column">
						<Heading size="sm" color="gray.800">
							{__(title, 'masteriyo')}
						</Heading>
						<Text color={`${colorScheme}.700`} fontWeight="bold" fontSize="md">
							{count}
						</Text>
					</Stack>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CountBox;
