const urls = {
	base: process.env.baseUrl + '/masteriyo/v1' || '/',
	wpMedia: process.env.baseUrl + '/wp/v2/media',
	courses: 'courses',
	categories: 'courses/categories',
	tags: 'courses/tags',
	difficulties: 'courses/difficulties',
	lessons: 'lessons',
	questions: 'questions',
	quizes: 'quizes',
	sections: 'sections',
	contents: 'sections/children',
	orders: 'orders',
	users: 'users',
	settings: 'settings',
};

export default urls;
