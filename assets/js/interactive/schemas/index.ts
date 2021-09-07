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
	item_type: 'quiz' | 'lesson';
	completed: boolean;
	video: boolean;
};

export type CourseProgressItemMap = {
	item_id: number;
	item_title: string;
	item_type: string;
	contents: [CourseContentMap];
};

export type CourseProgressMap = {
	id: number;
	name: string;
	user_id: number;
	course_id: 9;
	status: 'begin' | 'complete';
	started_at: string;
	modified_at: string;
	completed_at: string | any;
	items: [CourseProgressItemMap];
	summary: CourseProgressSummaryMap;
	course_permalink: string;
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

export type QuestionAnswerSchema = {
	id: number;
	course_id: number;
	user_name: string;
	user_email: string;
	created_at: string;
	content: string;
	parent: number;
	user_id: number;
	sender: 'student' | 'instructor';
	by_current_user: boolean;
	answers_count: number;
};

export type ScoreBoardSchema = {
	id: number;
	course_id: number;
	quiz_id: number;
	user_id: number;
	total_questions: number;
	total_answered_questions: number;
	total_marks: string;
	total_attempts: number;
	earned_marks: string;
	info: any;
	attempt_status: string;
	attempt_started_at: string;
	attempt_ended_at: string;
};
