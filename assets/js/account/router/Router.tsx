import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import Dashboard from '../pages/Dashboard';
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
				<Route path={routes.orderhistory}>
					<OrderHistory />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
