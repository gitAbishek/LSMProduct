import './assets/scss/style.scss';

import * as screens from './screens';

import { QueryClient, QueryClientProvider } from 'react-query';
import {
	Redirect,
	Route,
	HashRouter as Router,
	Switch,
} from 'react-router-dom';

import React from 'react';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<QueryClientProvider client={queryClient}>
			<Router>
				<div className="masteriyo">
					<Switch>
						<Route path="/courses" exact>
							<screens.AllCourses />
						</Route>
						<Route path="/courses/add-new-course" exact>
							<screens.AddNewCourse />
						</Route>
						<Route path="/courses/:courseId" exact>
							<screens.SectionBuilder />
						</Route>
						<Route path="/courses/:courseId/add-new-lesson" exact>
							<screens.AddNewLesson />
						</Route>
						<Route path="/courses/edit/:courseId" exact>
							<screens.EditCourse />
						</Route>
						{/* <Route path="/settings" exact>
							<screens.Settings />
						</Route>
						<Route path="/" exact>
							<Redirect to="/courses" />
						</Route>
						*/}
						<Route>
							<Redirect to="/courses" />
						</Route>
					</Switch>
				</div>
			</Router>
		</QueryClientProvider>
	);
};

export default App;
