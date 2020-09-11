import * as React from '@wordpress/element';

import {
	HashRouter as Router,
	Switch,
	Route,
	Redirect,
} from 'react-router-dom';

import * as screens from './screens';

export default class App extends React.Component {
	render() {
		return (
			<Router>
				<div className="masteriyo">
					<Switch>
						<Route path="/dashboard">
							<screens.Dashboard />
						</Route>
						<Route exact path="/">
							<Redirect to="/dashboard" />
						</Route>
					</Switch>
				</div>
			</Router>
		);
	}
}
