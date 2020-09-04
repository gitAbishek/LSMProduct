/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default class Tags extends Component {
	render() {
		return (
			<h1>{ __( 'Tags', 'masteriyo' ) }</h1>
		);
	}
}
