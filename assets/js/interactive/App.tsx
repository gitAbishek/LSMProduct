import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import RTLProvider from '../back-end/context/RTLProvider';
import ErrorBoundary from '../back-end/errors/ErrorBoundary';
import theme from '../back-end/theme/theme';
import { isProduction } from '../back-end/utils/utils';
import Router from './router/Router';

const App = () => {
	const queryClient = new QueryClient({
		defaultOptions: {
			queries: {
				refetchOnWindowFocus: false,
				refetchOnReconnect: false,
				useErrorBoundary: isProduction,
			},
		},
	});

	return (
		<ErrorBoundary>
			<RTLProvider>
				<ChakraProvider theme={theme}>
					<QueryClientProvider client={queryClient}>
						<ReactQueryDevtools initialIsOpen={false} />
						<Router />
					</QueryClientProvider>
				</ChakraProvider>
			</RTLProvider>
		</ErrorBoundary>
	);
};

export default App;
