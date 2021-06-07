import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';

import routes from '../constants/routes';
import * as screens from '../screens';
import AddNewQuiz from '../screens/quiz/AddNewQuiz';
import EditQuiz from '../screens/quiz/EditQuiz';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.orders.list} exact>
					<screens.AllOrders />
				</Route>
				<Route path={routes.courses.list} exact>
					<screens.AllCourses />
				</Route>
				<Route path={routes.courses.add} exact>
					<screens.AddNewCourse />
				</Route>
				<Route path={routes.builder} exact>
					<screens.Builder />
				</Route>
				<Route path={routes.lesson.add} exact>
					<screens.AddNewLesson />
				</Route>
				<Route path={routes.lesson.edit} exact>
					<screens.EditLesson />
				</Route>
				<Route path={routes.quiz.add}>
					<AddNewQuiz />
				</Route>
				<Route path={routes.quiz.edit} exact>
					<EditQuiz />
				</Route>
				<Route path={routes.settings} exact>
					<screens.Settings />
				</Route>
				<Route>
					<screens.FourOFour />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
