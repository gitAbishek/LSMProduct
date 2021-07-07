export type ContentNavigationButtonSchema = {
	id: number;
	name: string;
	type: string;
	parent: {
		id: number;
		name: string;
	};
};
export type ContentNavigationSchema = {
	previous: ContentNavigationButtonSchema;
	next: ContentNavigationButtonSchema;
};

export type CourseProgressSummaryMap = {
	total: {
		completed: number;
		pending: number;
	};
	lesson: {
		completed: number;
		pending: number;
	};
	quiz: {
		completed: number;
		pending: number;
	};
};

export type CourseContentMap = {
	item_id: number;
	item_title: string;
	item_type: string;
	completed: boolean;
};

export type CourseProgressItemMap = {
	item_id: number;
	item_title: string;
	item_type: string;
	contents: [CourseContentMap];
};

export type CourseProgressMap = {
	id: number;
	user_id: number;
	course_id: 9;
	status: 'begin' | 'complete';
	started_at: string;
	modified_at: string;
	completed_at: string | any;
	items: [CourseProgressItemMap];
	summary: CourseProgressSummaryMap;
};

export type CourseProgressItemsMap = {
	id?: number;
	progress_id?: number;
	course_id?: number;
	user_id?: number;
	item_id?: number;
	item_type?: 'lesson';
	completed?: boolean;
	started_at?: string;
	modified_at?: string;
	completed_at?: string | any;
};
