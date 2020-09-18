import { React } from '@wordpress/element';

import {
	HashRouter as Router,
	Switch,
	Route,
	Redirect,
} from 'react-router-dom';

import * as screens from './screens';

import './app.css';

const App = () => {
	return (
		<Router>
			<div className="masteriyo">
				<Switch>
					<Route path="/dashboard">
						<screens.Dashboard />
					</Route>
					<Route path="/builder">
						<screens.CourseBuilder />
					</Route>
					<Route path="/builder">
						<screens.Settings />
					</Route>
					<Route exact path="/">
						<Redirect to="/dashboard" />
					</Route>
				</Switch>
			</div>
		</Router>
	);
};

export default App;
