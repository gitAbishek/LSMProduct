export type QuizSchema = {
	id: number;
	name: string;
	slug: string;
	permalink: string;
	parent_id: number;
	course_id: number;
	menu_order: number;
	description: string;
	short_description: string;
	pass_mark: number;
	full_mark: number;
	duration: number;
	questions_count: number;
	navigation: {
		previous: {
			id: number;
			name: string;
			type: string;
			parent: {
				id: number;
				name: string;
			};
		};
		next: {
			id: number;
			name: string;
			type: string;
			parent: {
				id: number;
				name: string;
			};
		};
	};
	_links: {
		self: [
			{
				href: string;
			}
		];
		collection: [
			{
				href: string;
			}
		];
		previous: [
			{
				href: string;
			}
		];
		next: [
			{
				href: string;
			}
		];
	};
};
