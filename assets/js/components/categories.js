/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default class Categories extends Component {
	render() {
		return (
			<h1>{ __( 'Categories', 'masteriyo' ) }</h1>
		);
	}
}
