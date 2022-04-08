import { CourseCategorySchema } from '../schemas';
import { CourseCategoryHierarchy } from '../types/course';

export const makeCategoriesHierarchy = (
	categories: CourseCategorySchema[],
	excludeId: number = -1
): CourseCategoryHierarchy[] => {
	let newList: CourseCategoryHierarchy[] = [];
	let includedCategoryIds: number[] = [];

	const makeCategoryChildren = (
		category: CourseCategorySchema,
		depth: number = 1
	) => {
		let children: CourseCategoryHierarchy[] = [];

		categories
			.filter((item) => item.parent_id === category.id && item.id !== excludeId)
			.forEach((child) => {
				includedCategoryIds.push(child.id);
				children.push({ ...child, depth });
				children = children.concat(makeCategoryChildren(child, depth + 1));
			});

		return children;
	};

	categories
		.filter((cat) => cat.parent_id === 0 && cat.id !== excludeId)
		.forEach((cat) => {
			includedCategoryIds.push(cat.id);
			newList.push({ ...cat, depth: 0 });
			newList = newList.concat(makeCategoryChildren(cat));
		});

	// Include the categories that were left out because of its top level parent not being present.
	newList = categories
		.filter((category) => !includedCategoryIds.includes(category.id))
		.map((category) => ({ ...category, depth: 0 }))
		.concat(newList);

	return newList;
};

export const makeSlug = (str: string): string => {
	return str?.toLowerCase().replace(/\s/g, '-');
};
