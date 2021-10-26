import { Alert, Box, Heading, Stack } from '@chakra-ui/react';
import React from 'react';

const Achievement = () => {
	return (
		<Box py={10}>
			<Stack px={10}>
				<Heading>My Achievements</Heading>
				<Box py={10}>
					<Alert colorScheme={'blue.200'}>
						You have no achievements yet. Enroll in course to get an
						achievements
					</Alert>
				</Box>
			</Stack>
		</Box>
	);
};

export default Achievement;
