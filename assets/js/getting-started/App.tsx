import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';

import theme from '../src/theme/theme';
import MainLayout from './components/MainLayout';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<ChakraProvider theme={theme}>
			<QueryClientProvider client={queryClient}>
				<MainLayout />
			</QueryClientProvider>
		</ChakraProvider>
	);
};

export default App;
