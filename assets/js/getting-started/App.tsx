import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import ErrorBoundary from '../back-end/errors/ErrorBoundary';
import theme from '../back-end/theme/theme';
import MainLayout from './components/MainLayout';

const App = () => {
	const queryClient = new QueryClient({
		defaultOptions: {
			queries: {
				useErrorBoundary: true,
			},
		},
	});

	return (
		<ErrorBoundary>
			<ChakraProvider theme={theme}>
				<QueryClientProvider client={queryClient}>
					<MainLayout />
				</QueryClientProvider>
			</ChakraProvider>
		</ErrorBoundary>
	);
};

export default App;
