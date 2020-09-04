/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default class Courses extends Component {
	render() {
		return (
			<h1>{ __( 'Courses', 'masteriyo' ) }</h1>
		);
	}
}
