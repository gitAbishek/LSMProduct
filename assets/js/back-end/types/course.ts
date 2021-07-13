export type AuthorMap = {
	id: number;
	display_name: string;
	avatar_url: string;
};

export type CourseLinksMap = {
	self: [{ href: string }];
	collection: [{ href: string }];
};

export type CourseDataMap = {
	id: number;
	name: string;
	slug: string;
	permalink: string;
	preview_permalink: string;
	status: string;
	description: string;
	short_description: string;
	reviews_allowed: boolean;
	paret_id: number;
	menu_order: number;
	author: AuthorMap;
	date_created: string;
	date_modified: string;
	featured: false;
	featured_image: number;
	price: number;
	regular_price: number;
	sale_price: number;
	categories: string[];
	tags: string[];
	difficulty_id: number;
	enrollment_limit: number;
	duration: number;
	access_mode: string;
	show_curriculum: boolean;
	_links: CourseLinksMap;
};
