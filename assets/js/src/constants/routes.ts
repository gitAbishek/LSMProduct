const routes = {
	courses: {
		list: '/courses/list',
		add: '/courses/add',
		edit: '/courses/:courseId',
	},
	builder: {
		course: {
			edit: '/builder/courses/:courseId',
		},
		lesson: {
			add: '/builder/lesson/:sectionId/add-new-lesson',
			edit: '/builder/lesson/:lessonId',
		},
		quiz: {
			add: '/builder/quiz/add-new-quiz',
			edit: '/builder/quiz/:quizId',
		},
	},
	settings: '/settings',
};

export default routes;
