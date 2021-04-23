import React from 'react';
import { Route, Switch } from 'react-router-dom';

import routes from '../constants/routes';
import * as screens from '../screens';
import AddNewQuiz from '../screens/quiz/AddNewQuiz';
import EditQuiz from '../screens/quiz/EditQuiz';

const MasteriyoRouter = () => {
	return (
		<Switch>
			<Route path={routes.courses.list} exact>
				<screens.AllCourses />
			</Route>
			<Route path={routes.courses.add} exact>
				<screens.AddNewCourse />
			</Route>
			<Route path={routes.courses.edit} exact>
				<screens.EditCourse />
			</Route>
			<Route path={routes.section} exact>
				<screens.SectionBuilder />
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
	);
};

export default MasteriyoRouter;
