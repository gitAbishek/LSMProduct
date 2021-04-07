import 'react-loader-spinner/dist/loader/css/react-spinner-loader.css';

import { ChakraProvider } from '@chakra-ui/react';
import { Main } from 'Components/layout';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ToastProvider } from 'react-toast-notifications';

import Router from './router/Router';
import theme from './theme/theme';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<ChakraProvider theme={theme}>
			<ToastProvider>
				<QueryClientProvider client={queryClient}>
					<Main>
						<Router />
					</Main>
				</QueryClientProvider>
			</ToastProvider>
		</ChakraProvider>
	);
};

export default App;
