import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';

import Router from './router/Router';
import theme from '../back-end/theme/theme';
import Interactive from './components/Interactive';

const App = () => {
	const queryClient = new QueryClient({
		defaultOptions: {
			queries: {
				refetchOnMount: false,
				refetchOnWindowFocus: false,
				refetchOnReconnect: false,
			},
		},
	});

	return (
		<ChakraProvider theme={theme}>
			<QueryClientProvider client={queryClient}>
				<Interactive></Interactive>
			</QueryClientProvider>
		</ChakraProvider>
	);
};

export default App;
