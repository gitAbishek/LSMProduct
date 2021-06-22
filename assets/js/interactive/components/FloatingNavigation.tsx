import { Box, Icon } from '@chakra-ui/react';
import { BiChevronRight } from 'react-icons/bi';
import React from 'react';

const FloatingNavigation = () => {
	const floatingBoxStyles = {
		position: 'fixed',
		top: '50vh',
		transform: 'translateY(-50%)',
		right: 0,
	};
	return (
		<>
			<Box sx={floatingBoxStyles}>
				<Icon as={BiChevronRight} fontSize="xxx-large" />
			</Box>
		</>
	);
};

export default FloatingNavigation;
