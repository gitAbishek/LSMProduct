import { Flex, FormControl, FormLabel, Switch } from '@chakra-ui/react';
import React from 'react';

const ParticipantVideo = () => {
	return (
		<FormControl>
			<Flex justifyContent="space-between">
				<FormLabel>Participant Video</FormLabel>
				<Switch />
			</Flex>
		</FormControl>
	);
};

export default ParticipantVideo;
