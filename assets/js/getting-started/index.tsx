import React from 'react';
import ReactDom from 'react-dom';

import App from './App';

const appRoot = document.getElementById('masteriyo-onboarding');

if (appRoot) {
	ReactDom.render(<App />, appRoot);
}
