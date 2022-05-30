export enum OrderStatus {
	Completed = 'completed',
	OnHold = 'on-hold',
	Failed = 'failed',
	Refunded = 'refunded',
	Cancelled = 'cancelled',
	Pending = 'pending',
	Trash = 'trash',
}

export enum ReviewStatus {
	approve = 'approve',
	hold = 'hold',
	spam = 'spam',
	trash = 'trash',
}

export enum CourseStatus {
	Any = 'any',
	Publish = 'publish',
	Draft = 'draft',
	Trash = 'trash',
}

export enum UserStatus {
	Active = 0,
	Ham = 0,
	Spam = 1,
	Inactive = 1000,
}

export enum IconType {
	TrueFalse = 'true-false',
	SingleChoice = 'single-choice',
	MultipleChoice = 'multiple-choice',
}
