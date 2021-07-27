import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import CreateCatModalProvicer from './context/CreateCatProvider';
import ErrorBoundary from './errors/ErrorBoundary';
import Router from './router/Router';
import theme from './theme/theme';

const App = () => {
	const queryClient = new QueryClient({
		defaultOptions: {
			queries: {
				refetchOnWindowFocus: false,
				refetchOnReconnect: false,
				useErrorBoundary: true,
			},
			mutations: {
				onMutate: (data) => {
					console.log(data);
				},
			},
		},
	});

	return (
		<ChakraProvider theme={theme}>
			<ErrorBoundary>
				<QueryClientProvider client={queryClient}>
					<CreateCatModalProvicer>
						<Router />
					</CreateCatModalProvicer>
				</QueryClientProvider>
			</ErrorBoundary>
		</ChakraProvider>
	);
};

export default App;
