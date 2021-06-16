import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import Interactive from '../components/Interactive';
import routes from '../constants/routes';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.course}>
					<Interactive />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
