import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import { Button } from 'antd';

export default class Dashboard extends Component {
	render() {
		return (
			<div>
				<h1>Hello</h1>
				<Link tp="/">this is home</Link>
				<Button>This is ant button</Button>
			</div>
		);
	}
}
