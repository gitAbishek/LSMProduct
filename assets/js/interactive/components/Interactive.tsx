import { Box } from '@chakra-ui/react';
import React from 'react';
import Router from '../router/Router';
import Header from './Header';

const Interactive: React.FC = () => {
	return (
		<Box>
			<Header />
			<Router />
		</Box>
	);
};

export default Interactive;
