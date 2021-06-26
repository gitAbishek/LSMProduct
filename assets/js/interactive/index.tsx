import React from 'react';
import ReactDom from 'react-dom';
import App from './App';

const appRoot = document.getElementById('masteriyo-interactive-course');

if (appRoot) {
	ReactDom.render(<App />, appRoot);
}
