/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default class Dashboard extends Component {
	render() {
		return (
			<h1>{ __( 'Dashboard', 'masteriyo' ) }</h1>
		);
	}
}
