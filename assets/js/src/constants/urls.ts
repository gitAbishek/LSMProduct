import { isProduction } from './../utils/helper';
const baseUrl = isProduction() ?  process.env.baseUrl;

const urls = {
	courses: `${baseUrl}/courses`,
	course: `${baseUrl}/courses/:id`,
	categories: `${baseUrl}/courses/categories`,
	category: `${baseUrl}/courses/categories/:id`,
	tags: `${baseUrl}/courses/tags`,
	tag: `${baseUrl}/courses/tags/:id`,
	difficulties: `${baseUrl}/courses/difficulties`,
	difficulty: `${baseUrl}/courses/difficulties/:id`,
	lessons: `${baseUrl}/lessons`,
	lesson: `${baseUrl}/lessons/:id`,
	questions: `${baseUrl}/questions`,
	question: `${baseUrl}/questions/:id`,
	checkAnswer: `${baseUrl}/questions/check_answer/:id/answer`,
	quizes: `${baseUrl}/quizes`,
	quiz: `${baseUrl}/quizes/:id`,
	sections: `${baseUrl}/sections`,
	section: `${baseUrl}/sections/:id`,
	contents: `${baseUrl}/sections/children`,
	orders: `${baseUrl}/orders`,
	order: `${baseUrl}/orders/:id`,
	users: `${baseUrl}/users`,
	user: `${baseUrl}/users/:id`,
	settings: `${baseUrl}/settings`,
};

export default urls;
