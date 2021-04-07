import { isProduction } from './../utils/helper';

const urls = {
	base: process.env.baseUrl || '/',
	courses: `courses`,
	course: `courses/:id`,
	categories: `courses/categories`,
	category: `courses/categories/:id`,
	tags: `courses/tags`,
	tag: `courses/tags/:id`,
	difficulties: `courses/difficulties`,
	difficulty: `courses/difficulties/:id`,
	lessons: `lessons`,
	lesson: `lessons/:id`,
	questions: `questions`,
	question: `questions/:id`,
	checkAnswer: `questions/check_answer/:id/answer`,
	quizes: `quizes`,
	quiz: `quizes/:id`,
	sections: `sections`,
	section: `sections/:id`,
	contents: `sections/children`,
	orders: `orders`,
	order: `orders/:id`,
	users: `users`,
	user: `users/:id`,
	settings: `settings`,
};

export default urls;
