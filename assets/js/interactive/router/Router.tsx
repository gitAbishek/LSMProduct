import React from 'react';
import { BrowserRouter, HashRouter, Route, Switch } from 'react-router-dom';
import Interactive from '../components/Interactive';

import routes from '../constants/routes';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<div>Something</div>
			<Switch>
				<Route exact path="/lesson">
					<div>Internal page</div>
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
