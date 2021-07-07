import React from 'react';
import { Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import { AddNewLesson, Builder, EditLesson } from '../screens';
import AddNewQuiz from '../screens/quiz/AddNewQuiz';
import EditQuiz from '../screens/quiz/EditQuiz';

const CourseRouter: React.FC = () => {
	return (
		<Switch>
			<Route path={routes.quiz.add}>
				<AddNewQuiz />
			</Route>
			<Route path={routes.quiz.edit} exact>
				<EditQuiz />
			</Route>
			<Route path={routes.courses.edit} exact>
				<Builder />
			</Route>
			<Route path={routes.lesson.add} exact>
				<AddNewLesson />
			</Route>
			<Route path={routes.lesson.edit} exact>
				<EditLesson />
			</Route>
		</Switch>
	);
};

export default CourseRouter;
