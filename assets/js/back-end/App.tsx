import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import CreateCatModalProvicer from './context/CreateCatProvider';
import Router from './router/Router';
import theme from './theme/theme';

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
				<CreateCatModalProvicer>
					<Router />
				</CreateCatModalProvicer>
			</QueryClientProvider>
		</ChakraProvider>
	);
};

export default App;
