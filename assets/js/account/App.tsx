import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import RTLProvider from '../back-end/context/RTLProvider';
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
		<ChakraProvider theme={theme}>
			<RTLProvider>
				<ErrorBoundary>
					<QueryClientProvider client={queryClient}>
						<ReactQueryDevtools initialIsOpen={false} />
						<Router />
					</QueryClientProvider>
				</ErrorBoundary>
			</RTLProvider>
		</ChakraProvider>
	);
};

export default App;
