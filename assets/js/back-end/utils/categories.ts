import { CourseCategorySchema } from '../schemas';
import { CourseCategoryHierarchy } from '../types/course';

export const makeCategoriesHierarchy = (
	categories: CourseCategorySchema[],
	excludeId: number = -1
): CourseCategoryHierarchy[] => {
	let newList: CourseCategoryHierarchy[] = [];

	const makeCategoryChildren = (
		category: CourseCategorySchema,
		depth: number = 1
	) => {
		let children: CourseCategoryHierarchy[] = [];

		categories
			.filter((item) => item.parent_id === category.id && item.id !== excludeId)
			.forEach((child) => {
				children.push({ ...child, depth });
				children = children.concat(makeCategoryChildren(child, depth + 1));
			});

		return children;
	};

	categories
		.filter((cat) => cat.parent_id === 0 && cat.id !== excludeId)
		.forEach((cat) => {
			newList.push({ ...cat, depth: 0 });
			newList = newList.concat(makeCategoryChildren(cat));
		});

	return newList;
};

export const makeSlug = (str: string): string => {
	return str?.toLowerCase().replace(/\s/g, '-');
};
