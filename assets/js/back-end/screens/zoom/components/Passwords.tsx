import { FormControl, FormLabel, Input } from '@chakra-ui/react';
import React from 'react';

const Passwords = () => {
	return (
		<FormControl>
			<FormLabel>Zoom Password</FormLabel>
			<Input placeholder="Your Zoom Password" type="password" />
		</FormControl>
	);
};

export default Passwords;
