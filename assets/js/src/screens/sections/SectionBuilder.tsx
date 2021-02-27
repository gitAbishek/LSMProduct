import { fetchCourse, fetchSections } from '../../utils/api';

import AddNewButton from 'Components/common/AddNewButton';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';
import Section from './components/Section';
import { __ } from '@wordpress/i18n';
import styled from 'styled-components';
import { useParams } from 'react-router-dom';
import { useQuery } from 'react-query';

// const dummyData = {
// 	contents: {
// 		'content-1': { id: 'content-1', title: 'hello world', type: 'lesson' },
// 		'content-2': {
// 			id: 'content-2',
// 			title: 'Content for section',
// 			type: 'quiz',
// 		},
// 	},
// 	sections: {
// 		'section-1': {
// 			id: 'section-1',
// 			title: 'Section 1',
// 			contentIds: ['content-1', 'content-2'],
// 			editing: false,
// 		},
// 		'section-2': {
// 			id: 'section-2',
// 			title: 'Section 2',
// 			contentIds: [],
// 			editing: false,
// 		},
// 		'section-3': {
// 			id: 'section-3',
// 			title: 'Section 3',
// 			contentIds: [],
// 			editing: false,
// 		},
// 	},
// 	sectionOrder: ['section-1', 'section-2', 'section-3'],
// };

const SectionBuilder = () => {
	const { courseId }: any = useParams();
	const { data: courseData, isError: isError, isLoading: isLoading } = useQuery(
		[`course${courseId}`, courseId],
		() => fetchCourse(courseId),
		{
			enabled: true,
		}
	);

	const { data: sectionsData, isLoading: loadingSections } = useQuery(
		['sections', courseId],
		() => fetchSections(courseId),
		{
			enabled: true,
		}
	);

	if (isLoading) {
		return <h1>Loading</h1>;
	}

	return (
		<>
			<MainToolbar />
			<div className="mto-container mto-mx-auto">
				{sectionsData?.map((section: any, index: number) => (
					<Section
						key={section.id}
						id={section.id}
						title={section.name}
						index={index}
						courseId={courseId}
					/>
				))}
				<div className="mto-flex mto-justify-center">
					<AddNewButton>{__('Add New Section', 'masteriyo')}</AddNewButton>
				</div>
				{/* </DragDropContext> */}
			</div>
		</>
	);
};

export default SectionBuilder;
