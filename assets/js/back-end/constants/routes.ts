const routes = {
	course: '/courses/:courseId',
	courses: {
		list: '/courses',
		add: '/courses/add-new-course',
		edit: '/courses/:courseId/edit',
		settings: '/courses/:courseId/settings',
	},
	orders: {
		list: '/orders',
		add: '/orders/create-new-order',
		edit: '/orders/:orderId',
	},
	section: '/courses/:courseId/section',
	lesson: {
		add: '/courses/:courseId/lesson/:sectionId/add-new-lesson',
		edit: '/courses/:courseId/lesson/edit/:lessonId',
	},
	quiz: {
		add: '/courses/:courseId/quiz/:sectionId/add-new-quiz',
		edit: '/courses/:courseId/quiz/edit/:quizId',
	},
	course_categories: {
		list: '/courses/categories',
		add: '/courses/categories/new',
		edit: '/courses/categories/:categoryId',
	},
	course_tags: {
		list: '/courses/tags',
		add: '/courses/tags/new',
		edit: '/courses/tags/:tagId',
	},
	users: {
		students: {
			list: '/users/students',
			add: '/users/students/new',
			edit: '/users/students/:userId',
		},
		instructors: {
			list: '/users/instructors',
			add: '/users/instructors/new',
			edit: '/users/instructors/:userId',
		},
	},
	quiz_attempts: {
		list: '/quiz-attempts',
		edit: '/quiz-attempts/:quizId',
	},
	settings: '/settings',

	addOns: '/add-ons',
	reviews: {
		list: '/reviews',
		add: '/reviews/new',
		edit: '/reviews/:reviewId/edit',
		replies: '/reviews/:reviewId/replies',
	},
	notFound: '/not-found',
};

export default routes;
