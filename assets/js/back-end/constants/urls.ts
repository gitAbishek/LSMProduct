const base = '/masteriyo/v1/';
const urls = {
	wpMedia: '/wp/v2/media',
	wpPages: '/wp/v2/pages',
	courses: base + 'courses',
	categories: base + 'courses/categories',
	tags: base + 'courses/tags',
	difficulties: base + 'courses/difficulties',
	lessons: base + 'lessons',
	questions: base + 'questions',
	quizes: base + 'quizes',
	quizesAttempts: base + 'quizes/attempts',
	sections: base + 'sections',
	contents: base + 'sections/children',
	orders: base + 'orders',
	order_items: base + 'order-items',
	users: base + 'users',
	settings: base + 'settings',
	builder: base + 'coursebuilder',
	courseProgress: base + 'course-progress',
	courseProgressItem: base + 'course-progress/items',
	qa: base + 'courses/questions-answers',
	countries: base + 'datas/countries',
	states: base + 'datas/states',
	currencies: base + 'datas/currencies',
	instructors: base + 'users/instructors',
};

export default urls;
