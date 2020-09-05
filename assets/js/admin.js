/**
 * External dependencies
 */
import { render } from '@wordpress/element';

/**
 * Internal dependencies
 */
import App from './screens/app';

const appRoot = document.getElementById( 'masteriyo' );

if ( appRoot ) {
	render( <App />, appRoot );
}
