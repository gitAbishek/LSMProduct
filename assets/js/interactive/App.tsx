import { ChakraProvider } from '@chakra-ui/react';
import React, { useContext } from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import { ThemeContext } from '../back-end/context/ThemeProvider';
import ErrorBoundary from '../back-end/errors/ErrorBoundary';
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

	const [theme] = useContext(ThemeContext);

	return (
		<ErrorBoundary>
			<ChakraProvider theme={theme}>
				<QueryClientProvider client={queryClient}>
					<ReactQueryDevtools initialIsOpen={false} />
					<Router />
				</QueryClientProvider>
			</ChakraProvider>
		</ErrorBoundary>
	);
};

export default App;
