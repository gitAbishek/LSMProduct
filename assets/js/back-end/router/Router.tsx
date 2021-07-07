import React from 'react';
import { HashRouter, Route, Switch } from 'react-router-dom';
import routes from '../constants/routes';
import * as screens from '../screens';

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
				<Route path={routes.course_difficulties.list} exact>
					<screens.AllCourseDifficulties />
				</Route>
				<Route path={routes.course_difficulties.add} exact>
					<screens.AddNewCourseDifficulty />
				</Route>
				<Route path={routes.course_difficulties.edit} exact>
					<screens.EditCourseDifficulty />
				</Route>
				<Route path={routes.course_tags.list} exact>
					<screens.AllCourseTags />
				</Route>
				<Route path={routes.course_tags.add} exact>
					<screens.AddNewCourseTag />
				</Route>
				<Route path={routes.course_tags.edit} exact>
					<screens.EditCourseTag />
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
