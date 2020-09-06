import { Component } from '@wordpress/element';
import { Link } from 'react-router-dom';
import './style.css';

export default class Dashboard extends Component {
	render() {
		return (
			<>
				<h1>Hello</h1>
				<Link tp="/">home</Link>
			</>
		);
	}
}
