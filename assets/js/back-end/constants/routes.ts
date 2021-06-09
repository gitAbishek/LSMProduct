const routes = {
	courses: {
		list: '/courses',
		add: '/courses/add-new-course',
		edit: '/courses/:courseId',
		settings: '/courses/:courseId/settings',
	},
	orders: {
		list: '/orders',
		edit: '/orders/:orderId',
	},
	section: '/builder/courses/:courseId',
	lesson: {
		add: '/builder/lesson/:sectionId/add-new-lesson',
		edit: '/builder/lesson/edit/:lessonId',
	},
	quiz: {
		add: '/builder/quiz/:sectionId/add-new-quiz',
		edit: '/builder/quiz/edit/:quizId',
	},
	course_categories: {
		list: '/courses/categories',
		add: '/courses/categories/new',
		edit: '/courses/categories/:categoryId',
	},
	course_difficulties: {
		list: '/courses/difficulties',
		add: '/courses/difficulties/new',
		edit: '/courses/difficulties/:difficultyId',
	},
	settings: '/settings',
	notFound: '/not-found',
	builder: '/builder/:courseId',
};

export default routes;
