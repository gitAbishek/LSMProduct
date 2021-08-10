import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import ErrorBoundary from '../back-end/errors/ErrorBoundary';
import theme from '../back-end/theme/theme';
import Router from './router/Router';

const App = () => {
	const queryClient = new QueryClient({
		defaultOptions: {
			queries: {
				refetchOnWindowFocus: false,
				refetchOnReconnect: false,
				useErrorBoundary: true,
			},
		},
	});

	return (
		<ErrorBoundary>
			<ChakraProvider theme={theme}>
				<QueryClientProvider client={queryClient}>
					<Router />
				</QueryClientProvider>
			</ChakraProvider>
		</ErrorBoundary>
	);
};

export default App;
