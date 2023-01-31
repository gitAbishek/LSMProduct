import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import React from 'react';

const Name = () => {
	return (
		<FormControl>
			<FormLabel>Zoom Name</FormLabel>
			<Input placeholder="Your Zoom Name" required />
		</FormControl>
	);
};

export default Name;
