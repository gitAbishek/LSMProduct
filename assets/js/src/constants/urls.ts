const base = '/masteriyo/v1/';
const urls = {
	base: process.env.baseUrl,
	wpMedia: '/wp/v2/media',
	courses: base + 'courses',
	categories: base + 'courses/categories',
	tags: base + 'courses/tags',
	difficulties: base + 'courses/difficulties',
	lessons: base + 'lessons',
	questions: base + 'questions',
	quizes: base + 'quizes',
	sections: base + 'sections',
	contents: base + 'sections/children',
	orders: base + 'orders',
	users: base + 'users',
	settings: base + 'settings',
};

export default urls;
