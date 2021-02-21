import './assets/css/masteriyo.css';

import App from './App';
import React from 'react';
import ReactDom from 'react-dom';

const appRoot = document.getElementById('masteriyo');

if (appRoot) {
	ReactDom.render(<App />, appRoot);
}
