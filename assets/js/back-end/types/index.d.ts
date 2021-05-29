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
};

export type CoursesSettingsMap = {
	placeholder_image: string;
	add_to_cart_behavior: string;
	per_page: string;
	enable_editing: boolean;
	category_base: string;
	tag_base: string;
	difficulty_base: string;
	single_course_permalink: string;
	single_lesson_permalink: string;
	single_quiz_permalink: string;
	single_section_permalink: string;
	enable_single_course_permalink: boolean;
	single_course_enable_editing: boolean;
	show_thumbnail: boolean;
	thumbnail_size: string;
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
		production_email: string;
		sandbox_enable: boolean;
		sandbox_email: string;
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
	template_debug_enable: boolean;
	debug_enable: boolean;
	styles_mode: string;
};

export type SetttingsMap = {
	general: GeneralSettingsMap;
	courses: CoursesSettingsMap;
	pages: PagesSettingsMap;
	payments: PaymentsSettingsMap;
	emails: EmailsSetttingsMap;
	advanced: AdvancedSettingsMap;
};
