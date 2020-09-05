/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default class Masteriyo extends Component {
	render() {
		return <h1>{ __( 'Masteriyo', 'masteriyo' ) }</h1>;
	}
}
