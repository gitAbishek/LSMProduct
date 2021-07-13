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
	attempts_allowed: number;
	questions_display_per_page: number;
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

export type TrueFalseSchema = {
	name: string;
	correct: boolean;
};

export type SingleChoicechema = {
	name: string;
	correct: boolean;
};

export type MultipleChoiceSchema = {
	name: string;
	correct: boolean;
};

export type QuestionSchema = {
	id: number;
	name: string;
	permalink: string;
	status: 'publish' | 'draft' | string;
	description: string;
	type:
		| 'true-false'
		| 'single-choice'
		| 'multiple-choice'
		| 'short-answer'
		| 'image-matching';
	parent_id: number;
	course_id: number;
	menu_order: number;
	answers: TrueFalseSchema | SingleChoicechema | MultipleChoiceSchema;
	answers_required: boolean;
	randomize: boolean;
	points: string;
	positive_feedback: string;
	negative_feedback: string;
	feedback: string;
};
