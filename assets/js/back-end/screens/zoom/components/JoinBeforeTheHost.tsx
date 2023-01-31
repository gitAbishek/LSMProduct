import { Flex, FormControl, FormLabel, Switch } from '@chakra-ui/react';
import React from 'react';

const JoinBeforeTheHost = () => {
	return (
		<FormControl>
			<Flex justifyContent="space-between">
				<FormLabel>Join Before The Host</FormLabel>
				<Switch />
			</Flex>
		</FormControl>
	);
};

export default JoinBeforeTheHost;
