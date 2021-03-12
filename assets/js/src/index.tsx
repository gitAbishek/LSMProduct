import './assets/css/masteriyo.css';
import './index.css';

import App from './App';
import React from 'react';
import ReactDom from 'react-dom';
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
