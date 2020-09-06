import { render } from '@wordpress/element';

import App from './App';

const appRoot = document.getElementById('masteriyo');

if (appRoot) {
  render(<App />, appRoot);
} else {
  console.log('not found');
}
