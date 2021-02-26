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

	console.log(sectionsData);

	// const onDragEnd = (result) => {
	// 	const { destination, source, draggableId, type } = result;
	// 	if (!destination) {
	// 		return;
	// 	}

	// 	// Reorder section move
	// 	if (type === 'section') {
	// 		const newSectionOrder = Array.from(data.sectionOrder);
	// 		newSectionOrder.splice(source.index, 1);
	// 		newSectionOrder.splice(destination.index, 0, draggableId);
	// 		setData({ ...data, sectionOrder: newSectionOrder });
	// 		return;
	// 	}

	// 	const start = data.sections[source.droppableId];
	// 	const finish = data.sections[destination.droppableId];

	// 	if (start === finish) {
	// 		const newContentIds = Array.from(start.contentIds);
	// 		newContentIds.splice(source.index, 1);
	// 		newContentIds.splice(destination.index, 0, draggableId);

	// 		const newSection = {
	// 			...start,
	// 			contentIds: newContentIds,
	// 		};

	// 		setData({
	// 			...data,
	// 			sections: {
	// 				...data.sections,
	// 				[newSection.id]: newSection,
	// 			},
	// 		});
	// 		return;
	// 	}

	// 	const startContentIds = Array.from(start.contentIds);
	// 	startContentIds.splice(source.index, 1);
	// 	const newStart = {
	// 		...start,
	// 		contentIds: startContentIds,
	// 	};

	// 	const finishContentIds = Array.from(finish.contentIds);
	// 	finishContentIds.splice(destination.index, 0, draggableId);
	// 	const newFinish = {
	// 		...finish,
	// 		contentIds: finishContentIds,
	// 	};

	// 	setData({
	// 		...data,
	// 		sections: {
	// 			...data.sections,
	// 			[newStart.id]: newStart,
	// 			[newFinish.id]: newFinish,
	// 		},
	// 	});
	// };

	// 	const addNewSection = () => {
	// 		setData({
	// 			...data,
	// 			sectionOrder: [...data.sectionOrder, 'section-4'],
	// 			sections: {
	// 				...data.sections,
	// 				'section-4': {
	// 					id: 'section-4',
	// 					title: 'Section 4',
	// 					contentIds: [],
	// 					editing: true,
	// 				},
	// 			},
	// 		});
	// 	};
	return (
		<>
			<MainToolbar />
			<div className="mto-container mto-mx-auto">
				{/* <DragDropContext>
					<Droppable type="section" droppableId="course-builder">
						{(provided) => (
							<div {...provided.droppableProps} ref={provided.innerRef}> */}
				{sectionsData?.map((section: any, index: number) => (
					<Section
						key={section.id}
						id={section.id}
						title={section.name}
						index={index}
						courseId={courseId}
					/>
				))}
				{/* {provided.placeholder}
							</div>
						)}
					</Droppable> */}
				<div className="mto-flex mto-justify-center">
					<AddNewButton>{__('Add New Section', 'masteriyo')}</AddNewButton>
				</div>
				{/* </DragDropContext> */}
			</div>
		</>
	);
};

export default SectionBuilder;
