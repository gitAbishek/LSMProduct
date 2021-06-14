import { Box } from '@chakra-ui/react';
import React from 'react';
import InteractiveRouter from '../router/InteractiveRouter';
import Header from './Header';
import Sidebar from './Sidebar';

const Interactive: React.FC = () => {
	return (
		<Box>
			<InteractiveRouter />
		</Box>
	);
};

export default Interactive;
