import 'focus-visible';
import React from 'react';
import ReactDom from 'react-dom';
import ThemeProvider from '../back-end/context/ThemeProvider';
import App from './App';

const appRoot = document.getElementById('masteriyo-interactive-course');

if (appRoot) {
	ReactDom.render(
		<ThemeProvider>
			<App />
		</ThemeProvider>,
		appRoot
	);
}
