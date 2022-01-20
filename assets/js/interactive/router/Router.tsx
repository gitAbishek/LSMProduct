import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import { InteractiveLesson, InteractiveQuiz } from '../pages';
import FourOFour from '../pages/not-found/FourOFour';

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
				<Route>
					<FourOFour />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
