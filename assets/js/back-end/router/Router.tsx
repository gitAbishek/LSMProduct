import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import * as screens from '../screens';
import AllCourseDifficulties from '../screens/course-difficulties/AllCourseDifficulties';
import EditCourseDifficulty from '../screens/course-difficulties/EditCourseDifficulty';
import AddNewQuiz from '../screens/quiz/AddNewQuiz';
import EditQuiz from '../screens/quiz/EditQuiz';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<Switch>
				<Route path={routes.orders.list} exact>
					<screens.AllOrders />
				</Route>
				<Route path={routes.orders.add} exact>
					<screens.CreateNewOrder />
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
				<Route path={routes.course_difficulties.list} exact>
					<AllCourseDifficulties />
				</Route>
				<Route path={routes.course_difficulties.edit} exact>
					<EditCourseDifficulty />
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
				<Route path={routes.users.students.add} exact>
					<screens.AddStudent />
				</Route>
				<Route path={routes.users.students.list} exact>
					<screens.Students />
				</Route>
				<Route path={routes.users.students.edit} exact>
					<screens.EditStudent />
				</Route>
				<Route path={routes.users.instructors.add} exact>
					<screens.AddInstructor />
				</Route>
				<Route path={routes.users.instructors.list} exact>
					<screens.Instructors />
				</Route>
				<Route path={routes.users.instructors.edit} exact>
					<screens.EditInstructor />
				</Route>
				<Route path={routes.quiz_attempts.list} exact>
					<screens.AllQuizAttempts />
				</Route>
				<Route path={routes.quiz_attempts.edit} exact>
					<screens.ReviewQuizAttempt />
				</Route>
				<Route path={routes.settings} exact>
					<screens.Settings />
				</Route>
				<Route path={routes.addOns} exact>
					<screens.AddOns />
				</Route>
				<Route path={routes.reviews.list} exact>
					<screens.AllReviews />
				</Route>
				<Route path={routes.reviews.edit} exact>
					<screens.EditReview />
				</Route>

				<Route>
					<screens.FourOFour />
				</Route>
			</Switch>
		</HashRouter>
	);
};

export default Router;
