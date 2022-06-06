import {
	Box,
	Center,
	Heading,
	Stack,
	Text,
	ThemingProps,
} from '@chakra-ui/react';
import React, { ReactNode } from 'react';

interface Props {
	count: number;
	title: string;
	colorScheme: ThemingProps['colorScheme'];
	icon: ReactNode;
	w: 'xs' | 'sm' | 'md' | 'lg' | '100%';
}

const CountBox: React.FC<Props> = (props) => {
	const { title, count, colorScheme, icon, w } = props;

	return (
		<Box w={w} borderWidth="1px" borderColor="gray.100">
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
						color={`${colorScheme}.500`}>
						{icon}
					</Center>
					<Stack direction="column">
						<Heading size="sm" color="gray.800">
							{title}
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

CountBox.defaultProps = {
	w: 'xs',
};

export default CountBox;
