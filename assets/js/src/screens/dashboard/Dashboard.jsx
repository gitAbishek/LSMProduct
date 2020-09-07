import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import './style.css';

export default class Dashboard extends Component {
	render() {
		return (
			<div>
				<h1>Hello</h1>
				<Link tp="/">this is home</Link>
			</div>
		);
	}
}
