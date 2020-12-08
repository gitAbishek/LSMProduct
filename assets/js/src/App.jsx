import './assets/scss/style.scss';

import * as screens from './screens';

import {
	Redirect,
	Route,
	HashRouter as Router,
	Switch,
} from 'react-router-dom';

import { React } from '@wordpress/element';

const App = () => {
	return (
		<Router>
			<div className="masteriyo">
				<Switch>
					<Route path="/course" exact>
						<screens.Course />
					</Route>
					<Route path="/builder" exact>
						<screens.CourseBuilder />
					</Route>
					<Route path="/settings" exact>
						<screens.Settings />
					</Route>
					<Route path="/" exact>
						<Redirect to="/course" />
					</Route>
				</Switch>
			</div>
		</Router>
	);
};

export default App;
