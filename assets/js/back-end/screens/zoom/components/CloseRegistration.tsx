import { Checkbox, Flex, FormControl } from '@chakra-ui/react';
import React from 'react';

const CloseRegistration = () => {
	return (
		<FormControl>
			<Flex gap="">
				<Checkbox>Close Registration</Checkbox>
			</Flex>
		</FormControl>
	);
};

export default CloseRegistration;
