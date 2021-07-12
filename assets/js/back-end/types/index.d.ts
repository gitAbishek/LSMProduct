export type GeneralSettingsMap = {
	country: string;
	city: string;
	address_line1: string;
	address_line2: string;
	postcode: string;
	currency: string;
	currency_position: string;
	thousand_separator: string;
	decimal_separator: string;
	number_of_decimals: number;
	primary_color: string;
	theme: string;
};

export type CoursesSettingsMap = {
	enable_search: boolean;
	placeholder_image: string;
	per_page: string;
	per_row: number;
	category_base: string;
	tag_base: string;
	difficulty_base: string;
	single_course_permalink: string;
	single_lesson_permalink: string;
	single_quiz_permalink: string;
	single_section_permalink: string;
	show_thumbnail: boolean;
	thumbnail_size: string;
	enable_review: boolean;
	enable_questions_answers: boolean;
};

export type QuizzesSettingsMap = {
	questions_display_per_page: number;
};

export type PagesSettingsMap = {
	myaccount_page_id: number;
	course_list_page_id: number;
	terms_conditions_page_id: number;
	checkout_page_id: number;
	checkout_endpoints: {
		pay: string;
		order_received: string;
		add_payment_method: string;
		delete_payment_method: string;
		set_default_payment_method: string;
	};
	account_endpoints: {
		orders: string;
		view_order: string;
		my_courses: string;
		edit_account: string;
		payment_methods: string;
		lost_password: string;
		logout: string;
	};
};

export type PaymentsSettingsMap = {
	paypal: {
		enable: boolean;
		title: string;
		description: string;
		ipn_email_notifications: boolean;
		sandbox: boolean;
		email: string;
		receiver_email: string;
		identity_token: string;
		invoice_prefix: string;
		payment_action: string;
		image_url: string;
		debug: boolean;
		sandbox_api_username: string;
		sandbox_api_password: string;
		sandbox_api_signature: string;
		live_api_username: string;
		live_api_password: string;
		live_api_signature: string;
	};
};

export type EmailsSetttingsMap = {
	general: {
		from_name: string;
		from_email: string;
		default_content: string;
		header_image: string;
		footer_text: string;
	};
	new_order: {
		enable: boolean;
		recipients: array;
		subject: string;
		heading: string;
		content: string;
	};
	processing_order: {
		enable: boolean;
		subject: string;
		heading: string;
		content: string;
	};
	completed_order: {
		enable: boolean;
		subject: string;
		heading: string;
		content: string;
	};
	onhold_order: {
		enable: boolean;
		subject: string;
		heading: string;
		content: string;
	};
	cancelled_order: {
		enable: boolean;
		recipients: array;
		subject: string;
		heading: string;
		content: string;
	};
	enrolled_course: {
		enable: boolean;
		subject: string;
		heading: string;
		content: string;
	};
	completed_course: {
		enable: boolean;
		subject: string;
		heading: string;
		content: string;
	};
	become_an_instructor: {
		enable: boolean;
		subject: string;
		heading: string;
		content: string;
	};
};

export type AdvancedSettingsMap = {
	template_debug: boolean;
	debug: boolean;
	style: string;
};

export type SetttingsMap = {
	general: GeneralSettingsMap;
	courses: CoursesSettingsMap;
	quizzes: QuizzesSettingsMap;
	pages: PagesSettingsMap;
	payments: PaymentsSettingsMap;
	emails: EmailsSetttingsMap;
	advance: AdvancedSettingsMap;
};
