import { Alert, Box, Heading, Stack } from '@chakra-ui/react';
import React from 'react';

const Achievement = () => {
	return (
		<Box py={10}>
			<Stack px={10}>
				<Heading as="h1" size="xl">
					My Achievements
				</Heading>
				<Box py={10}>
					<Alert colorScheme={'blue.200'} color={'blue.400'} borderRadius={5}>
						You have no achievements yet. Enroll in course to get an
						achievements
					</Alert>
				</Box>
			</Stack>
		</Box>
	);
};

export default Achievement;
