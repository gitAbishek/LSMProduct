import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import Dashboard from '../pages/Dashboard';
const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.dashboard} exact>
					<Dashboard />
				</Route>
				<Route>
					<Dashboard />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
