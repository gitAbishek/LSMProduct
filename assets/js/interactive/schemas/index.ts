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
