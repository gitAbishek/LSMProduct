import { Component } from '@wordpress/element';

import { __ } from '@wordpress/i18n';

import { HashRouter as Router, Switch, Route } from 'react-router-dom';

import * as screens from './screens';

export default class App extends Component {
	render() {
		return (
			<Router>
				<div className="masteriyo">
					<Switch>
						<Route path="/dashboard">
							<screens.Dashboard />
						</Route>
					</Switch>
				</div>
			</Router>
		);
	}
}
