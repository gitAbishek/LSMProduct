const routes = {
	courses: {
		list: '/courses',
		add: '/courses/add-new-course',
		edit: '/courses/:courseId',
		settings: '/courses/:courseId/settings',
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
	settings: '/settings',
	notFound: '/not-found',
	builder: '/builder/:courseId',
};

export default routes;