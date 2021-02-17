import './assets/scss/style.scss';

import * as screens from './screens';

import { QueryClient, QueryClientProvider } from 'react-query';
import {
	Redirect,
	Route,
	HashRouter as Router,
	Switch,
} from 'react-router-dom';

import { React } from '@wordpress/element';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<QueryClientProvider client={queryClient}>
			<Router>
				<div className="masteriyo">
					<Switch>
						<Route path="/course" exact>
							<screens.Course />
						</Route>
						<Route path="/courses" exact>
							<screens.Courses />
						</Route>
						<Route path="/builder" exact>
							<screens.CourseBuilder />
						</Route>
						<Route path="/settings" exact>
							<screens.Settings />
						</Route>
						<Route path="/:courseId/add-new-lesson" exact>
							<screens.AddNewLesson />
						</Route>
						<Route path="/" exact>
							<Redirect to="/courses" />
						</Route>
						<Route>
							<Redirect to ="/courses" />
						</Route>
					</Switch>
				</div>
			</Router>
		</QueryClientProvider>
	);
};

export default App;
