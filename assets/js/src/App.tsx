import 'react-loader-spinner/dist/loader/css/react-spinner-loader.css';

import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import { ToastProvider } from 'react-toast-notifications';

import theme from './theme/theme';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<ChakraProvider theme={theme}>
			<ToastProvider>
				<QueryClientProvider client={queryClient}></QueryClientProvider>
			</ToastProvider>
		</ChakraProvider>
	);
};

export default App;
