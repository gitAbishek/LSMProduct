import { React } from '@wordpress/element';
import {
	HashRouter as Router,
	Redirect,
	Route,
	Switch,
} from 'react-router-dom';
import './app.css';
import * as screens from './screens';

const App = () => {
	return (
		<Router>
			<div className="masteriyo">
				<Switch>
					<Route path="/course">
						<screens.Course />
					</Route>
					<Route path="/builder">
						<screens.CourseBuilder />
					</Route>
					<Route path="/settings">
						<screens.Settings />
					</Route>
					<Route exact path="/">
						<Redirect to="/course" />
					</Route>
				</Switch>
			</div>
		</Router>
	);
};

export default App;
