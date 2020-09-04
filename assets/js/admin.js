/**
 * External dependencies
 */
import { render } from '@wordpress/element';
import { BrowserRouter, Route, Switch } from 'react-router-dom';

/**
 * Internal dependencies
 */
import App from './components/app';

const appRoot = document.getElementById( 'masteriyo' );

if ( appRoot ) {
	render( <App />, appRoot );
}
