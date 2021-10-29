import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
<<<<<<< HEAD
import MyCourses from '../pages/courses/MyCouses';
import Dashboard from '../pages/dashboard/Dashboard';
import Header from '../pages/Header';
=======
import Dashboard from '../pages/Dashboard';
>>>>>>> 10dd25df (implemented table)

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
				s{' '}
			</Switch>
		</HashRouter>
	);
};

export default Router;
