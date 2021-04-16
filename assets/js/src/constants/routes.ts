const routes = {
	courses: {
		list: '/courses',
		add: '/builder',
		edit: '/courses/:courseId',
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

	settings: 'settings',
};

export default routes;
