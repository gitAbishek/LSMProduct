import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';
import CreateCatModalProvicer from './context/CreateCatProvider';
import MasteriyoProvider from './context/MasteriyoProvider';
import ErrorBoundary from './errors/ErrorBoundary';
import Router from './router/Router';
import theme from './theme/theme';
import { isProduction } from './utils/utils';

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
		<ChakraProvider theme={theme}>
			<ErrorBoundary>
				<MasteriyoProvider>
					<QueryClientProvider client={queryClient}>
						<ReactQueryDevtools initialIsOpen={false} />
						<CreateCatModalProvicer>
							<Router />
						</CreateCatModalProvicer>
					</QueryClientProvider>
				</MasteriyoProvider>
			</ErrorBoundary>
		</ChakraProvider>
	);
};

export default App;
