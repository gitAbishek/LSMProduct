import { Flex, FormControl, FormLabel, Switch } from '@chakra-ui/react';
import React from 'react';

const MuteUponEntry = () => {
	return (
		<FormControl>
			<Flex justifyContent="space-between">
				<FormLabel>Mute Upon Entry</FormLabel>
				<Switch />
			</Flex>
		</FormControl>
	);
};

export default MuteUponEntry;
