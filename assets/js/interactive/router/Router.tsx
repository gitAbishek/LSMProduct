import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';

import routes from '../constants/routes';
import { InteractiveLesson } from '../pages';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.lesson} exact>
					<InteractiveLesson />
				</Route>

				<Route>
					<InteractiveLesson />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
