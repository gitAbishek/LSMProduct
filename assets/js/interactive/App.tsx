import { ChakraProvider } from '@chakra-ui/react';
import 'focus-visible';
import { createStore, StateMachineProvider } from 'little-state-machine';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import theme from '../back-end/theme/theme';
import Router from './router/Router';

createStore({
	quizProgress: {},
});

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
		<StateMachineProvider>
			<ChakraProvider theme={theme}>
				<QueryClientProvider client={queryClient}>
					<Router />
				</QueryClientProvider>
			</ChakraProvider>
		</StateMachineProvider>
	);
};

export default App;
