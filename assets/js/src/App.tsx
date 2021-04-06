import 'react-loader-spinner/dist/loader/css/react-spinner-loader.css';

import { ChakraProvider } from '@chakra-ui/react';
import React from 'react';
import { QueryClient, QueryClientProvider } from 'react-query';
import {
	Redirect,
	Route,
	HashRouter as Router,
	Switch,
} from 'react-router-dom';
import { ToastProvider } from 'react-toast-notifications';

import * as screens from './screens';
import AddNewQuiz from './screens/quiz/AddNewQuiz';
import EditQuiz from './screens/quiz/EditQuiz';

const App = () => {
	const queryClient = new QueryClient();

	return (
		<ChakraProvider>
			<ToastProvider>
				<QueryClientProvider client={queryClient}>
					<Router>
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
					</Router>
				</QueryClientProvider>
			</ToastProvider>
		</ChakraProvider>
	);
};

export default App;
