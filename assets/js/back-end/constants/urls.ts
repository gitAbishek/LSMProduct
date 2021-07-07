const base = '/masteriyo/v1/';
const urls = {
	base: process.env.baseUrl,
	wpMedia: '/wp/v2/media',
	wpPages: '/wp/v2/pages',
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
	order_items: base + 'order-items',
	users: '/wp/v2/users',
	settings: base + 'settings',
	builder: base + 'coursebuilder',
	courseProgress: base + 'course-progress',
	courseProgressItem: base + 'course-progress/items',
};

export default urls;
