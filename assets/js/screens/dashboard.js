/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import Button from '../components/common/button';
import 'antd/dist/antd.less';

export default class Dashboard extends Component {
	render() {
		return (
			<>
				<h1>{ __( 'Dashboard', 'masteriyo' ) }</h1>
				<Button type="primary">This is ant button</Button>
			</>
		);
	}
}
