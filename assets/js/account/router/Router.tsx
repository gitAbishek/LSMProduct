import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import MyCourses from '../pages/courses/MyCouses';
import Dashboard from '../pages/dashboard/Dashboard';
import Header from '../pages/Header';
import OrderHistory from '../pages/order-history/OrderHistory';

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
				<Route path={routes.myOrderHistory}>
					<OrderHistory />
				</Route>
				<Route>
					<Dashboard />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
