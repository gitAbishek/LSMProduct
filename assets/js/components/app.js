/**
 * External dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { HashRouter as Router, Route, Link, Switch, NavLink } from 'react-router-dom';

import Categories from './categories';
import Tags from './tags';
import Courses from './courses';
import Dashboard from './dashboard';
import Masteriyo from './masteriyo';


export default class App extends Component {
	render() {
		return (
			<Router>
				<div className="masteriyo">
					<nav>
						<header className="masteriyo-header">
							{ __( 'Masteriyo Live Reload Test', 'masteriyo' ) }
						</header>
					</nav>

					<Switch>
						<Route path="/dashboard">
							<Dashboard />
						</Route>
						<Route path="/courses">
							<Courses />
						</Route>
						<Route path="/categories">
							<Categories />
						</Route>
						<Route path="/tags">
							<Tags/>
						</Route>
						<Route>
							<Masteriyo />
						</Route>
					</Switch>
				</div>
			</Router>
		);
	}
}
