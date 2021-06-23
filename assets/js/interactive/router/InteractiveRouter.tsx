import React from 'react';
import { Route, Switch } from 'react-router-dom';

import routes from '../constants/routes';
import { InteractiveLesson, InteractiveQuiz } from '../pages';

const InteractiveRouter: React.FC = () => {
	return (
		<Switch>
			<Route path={routes.lesson} exact>
				<InteractiveLesson />
			</Route>
			<Route path={routes.quiz} exact>
				<InteractiveQuiz />
			</Route>
		</Switch>
	);
};

export default InteractiveRouter;
