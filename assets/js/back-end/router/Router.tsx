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
				<Route path={routes.orders.edit} exact>
					<screens.EditOrder />
				</Route>
				<Route path={routes.courses.list} exact>
					<screens.AllCourses />
				</Route>
				<Route path={routes.courses.add} exact>
					<screens.AddNewCourse />
				</Route>
				<Route path={routes.course_categories.list} exact>
					<screens.AllCourseCategories />
				</Route>
				<Route path={routes.course_categories.add} exact>
					<screens.AddNewCourseCategory />
				</Route>
				<Route path={routes.course_categories.edit} exact>
					<screens.EditCourseCategory />
				</Route>
				<Route path={routes.quiz.add} exact>
					<AddNewQuiz />
				</Route>
				<Route path={routes.quiz.edit} exact>
					<EditQuiz />
				</Route>
				<Route path={routes.courses.edit} exact>
					<screens.Builder />
				</Route>
				<Route path={routes.lesson.add} exact>
					<screens.AddNewLesson />
				</Route>
				<Route path={routes.lesson.edit} exact>
					<screens.EditLesson />
				</Route>
				<Route path={routes.users.studentsList} exact>
					<screens.Students />
				</Route>
				<Route path={routes.users.instructorsList} exact>
					<screens.Instructors />
				</Route>
				<Route path={routes.users.edit} exact>
					<screens.EditUser />
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
