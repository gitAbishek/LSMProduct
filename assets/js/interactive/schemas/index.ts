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

export type CourseProgressMap = {
	id: number;
	user_id: number;
	course_id: 9;
	status: string;
	date_start: string;
	date_update: string;
	date_completed: string | any;
	items: [];
	summary: CourseProgressSummaryMap;
};
