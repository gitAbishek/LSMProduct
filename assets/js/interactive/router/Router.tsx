import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import {
	FourOFour,
	InteractiveCourse,
	InteractiveLesson,
	InteractiveQuiz,
} from '../pages';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.course} exact>
					<InteractiveCourse />
				</Route>
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
