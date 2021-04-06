import React from 'react';
import { HashRouter, Redirect, Route, Switch } from 'react-router-dom';
import * as screens from 'screens';
import AddNewQuiz from 'screens/quiz/AddNewQuiz';
import EditQuiz from 'screens/quiz/EditQuiz';

const Router: React.FC = () => {
	return (
		<HashRouter>
			<div className="masteriyo">
				<Switch>
					<Route path="/courses" exact>
						<screens.AllCourses />
					</Route>
					<Route path="/courses/add-new-course" exact>
						<screens.AddNewCourse />
					</Route>
					<Route path="/builder/:courseId" exact>
						<screens.SectionBuilder />
					</Route>
					<Route path="/courses/:sectionId/add-new-lesson" exact>
						<screens.AddNewLesson />
					</Route>
					<Route path="/courses/:sectionId/add-new-quiz" exact>
						<AddNewQuiz />
					</Route>
					<Route path="/quiz/:quizId/edit/:step?" exact>
						<EditQuiz />
					</Route>
					<Route path="/builder/lesson/:lessonId" exact>
						<screens.EditLesson />
					</Route>
					<Route path="/courses/edit/:courseId" exact>
						<screens.EditCourse />
					</Route>
					<Route path="/settings" exact>
						<screens.Settings />
					</Route>

					<Route>
						<Redirect to="/courses" />
					</Route>
				</Switch>
			</div>
		</HashRouter>
	);
};

export default Router;
