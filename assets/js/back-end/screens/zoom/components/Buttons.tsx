import { Button, Flex } from '@chakra-ui/react';
import React from 'react';

const Buttons = () => {
	return (
		<Flex gap="5">
			<Button variant="solid" colorScheme="blue" type="submit">
				Add New Zoom
			</Button>
			<Button variant="outline">Cancel</Button>
		</Flex>
	);
};

export default Buttons;
