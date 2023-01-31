import { Flex, FormControl, FormLabel, Switch } from '@chakra-ui/react';
import React from 'react';

const FocusMode = () => {
	return (
		<FormControl>
			<Flex justifyContent="space-between">
				<FormLabel>Focus Mode</FormLabel>
				<Switch />
			</Flex>
		</FormControl>
	);
};

export default FocusMode;
