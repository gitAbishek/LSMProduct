import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';

import routes from '../constants/routes';
import { InteractiveLesson } from '../pages';

const InteractiveRouter: React.FC = () => {
	return (
		<Switch>
			<Route path="lesson" exact>
				<InteractiveLesson />
			</Route>
		</Switch>
	);
};

export default InteractiveRouter;
