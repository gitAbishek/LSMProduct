import { Center, Spinner } from '@chakra-ui/react';
import React from 'react';

const FullScreenLoader = () => {
	return (
		<Center h="calc(100vh - 60px)">
			<Spinner />
		</Center>
	);
};

export default FullScreenLoader;
