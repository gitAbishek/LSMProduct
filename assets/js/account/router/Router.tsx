import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
// import Courses from '../pages/courses/Courses';
import Dashboard from '../pages/Dashboard';
import Header from '../pages/Header';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Header />
			<Switch>
				<Route path={routes.dashboard}>
					<Dashboard />
				</Route>
				{/* <Route path={routes.mycourses} exact>
					<Courses />
				</Route> */}
				<Route>
					<Dashboard />
					{/* <Courses /> */}
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
