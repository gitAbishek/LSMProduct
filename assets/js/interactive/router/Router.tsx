import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import { InteractiveLesson, InteractiveQuiz } from '../pages';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.lesson} exact>
					<InteractiveLesson />
				</Route>
				<Route path={routes.quiz} exact>
					<InteractiveQuiz />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
