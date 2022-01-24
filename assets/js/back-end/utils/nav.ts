import routes from '../../interactive/constants/routes';

export const getNavigationRoute = (
	id: number,
	type: string,
	courseId: number
) => {
	if (type === 'lesson') {
		return routes.lesson
			.replace(':lessonId', id.toString())
			.replace(':courseId', courseId.toString());
	} else if (type === 'quiz') {
		return routes.quiz
			.replace(':quizId', id.toString())
			.replace(':courseId', courseId.toString());
	}
	return '';
};
