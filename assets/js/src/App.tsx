import { Box, ChakraProvider, Container } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';

import Router from './router/Router';
import theme from './theme/theme';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<ChakraProvider theme={theme}>
			<QueryClientProvider client={queryClient}>
				<Box id="masteriyo">
					<Router />
				</Box>
			</QueryClientProvider>
		</ChakraProvider>
	);
};

export default App;
