import React from 'react';
import Builder from './Builder';
import Course from './Course';
import Settings from './Settings';

const CourseSkeleton = (props: { page: number }) => {
	const { page } = props;

	const selectCoursesTab = (p: number) => {
		switch (p) {
			case 1:
				return <Builder />;
			case 2:
				return <Settings />;
			default:
				return <Course />;
		}
	};

	return selectCoursesTab(page);
};

export default CourseSkeleton;
