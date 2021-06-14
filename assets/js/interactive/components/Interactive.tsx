import { Box } from '@chakra-ui/react';
import React from 'react';
import Router from '../router/Router';
import Header from './Header';
import Sidebar from './Sidebar';

const Interactive: React.FC = () => {
	return (
		<Box>
			<Header />
			<Sidebar />
		</Box>
	);
};

export default Interactive;
