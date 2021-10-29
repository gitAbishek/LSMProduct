import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import MyCourses from '../pages/courses/MyCouses';
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
				<Route path={routes.myCourses}>
					<MyCourses />
				</Route>
				<Route>
					<Dashboard />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
