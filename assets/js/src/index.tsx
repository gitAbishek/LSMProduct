import React from 'react';
import ReactDom from 'react-dom';

import App from './App';
import { ThemeProvider } from './context/ThemeContext';

const appRoot = document.getElementById('masteriyo');

if (appRoot) {
	ReactDom.render(
		<ThemeProvider>
			<App />
		</ThemeProvider>,
		appRoot
	);
}
