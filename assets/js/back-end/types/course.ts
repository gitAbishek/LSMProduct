import { CourseCategorySchema, CourseSchema } from '../schemas';

export interface AuthorMap {
	id: number;
	display_name: string;
	avatar_url: string;
}

export interface CourseLinksMap {
	self: [{ href: string }];
	collection: [{ href: string }];
}

export interface CourseDataMap {
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
	price_type: string;
	categories: string[];
	tags: string[];
	difficulty_id: number;
	enrollment_limit: number;
	duration: number;
	access_mode: string;
	show_curriculum: boolean;
	_links: CourseLinksMap;
	highlights: string;
	edit_post_link: string;
}

export interface CoursesResponse {
	data: CourseSchema[];
	meta: {
		current_page: number;
		pages: number;
		per_page: number;
		total: number;
	};
}

export interface CourseCategoriesResponse {
	data: CourseCategorySchema[];
	meta: {
		current_page: number;
		pages: number;
		per_page: number;
		total: number;
	};
}

export interface CourseCategoryHierarchy extends CourseCategorySchema {
	depth: number;
}

export interface CoursesApiResponse {
	data: CourseSchema[];
	meta: {
		current_page: number;
		pages: number;
		per_page: number;
		total: number;
	};
}
