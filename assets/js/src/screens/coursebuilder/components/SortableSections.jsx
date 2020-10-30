import { React, useState } from '@wordpress/element';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';
import Section from './Section';
import Container from '../../../components/common/Container';

const dummyData = {
	contents: {
		'content-1': { id: 'content-1', title: 'hello world', type: 'lesson' },
		'content-2': {
			id: 'content-2',
			title: 'Content for section',
			type: 'quiz',
		},
	},
	sections: {
		'section-1': {
			id: 'section-1',
			title: 'Section 1',
			contentIds: ['content-1', 'content-2'],
		},
		'section-2': {
			id: 'section-2',
			title: 'Section 2',
			contentIds: [],
		},
		'section-3': {
			id: 'section-3',
			title: 'Section 3',
			contentIds: [],
		},
	},
	sectionOrder: ['section-1', 'section-2', 'section-3'],
};

const SortableSections = () => {
	const [data, setData] = useState(dummyData);

	const onDragEnd = (result) => {
		const { destination, source, draggableId, type } = result;
		if (!destination) {
			return;
		}

		// Reorder section move
		if (type === 'section') {
			const newSectionOrder = Array.from(data.sectionOrder);
			newSectionOrder.splice(source.index, 1);
			newSectionOrder.splice(destination.index, 0, draggableId);
			setData({ ...data, sectionOrder: newSectionOrder });
			return;
		}

		const start = data.sections[source.droppableId];
		const finish = data.sections[destination.droppableId];

		if (start === finish) {
			const newContentIds = Array.from(start.contentIds);
			newContentIds.splice(source.index, 1);
			newContentIds.splice(destination.index, 0, draggableId);

			const newSection = {
				...start,
				contentIds: newContentIds,
			};

			setData({
				...data,
				sections: {
					...data.sections,
					[newSection.id]: newSection,
				},
			});
			return;
		}

		const startContentIds = Array.from(start.contentIds);
		startContentIds.splice(source.index, 1);
		const newStart = {
			...start,
			contentIds: startContentIds,
		};

		const finishContentIds = Array.from(finish.contentIds);
		finishContentIds.splice(destination.index, 0, draggableId);
		const newFinish = {
			...finish,
			contentIds: finishContentIds,
		};

		setData({
			...data,
			sections: {
				...data.sections,
				[newStart.id]: newStart,
				[newFinish.id]: newFinish,
			},
		});
	};

	return (
		<Container>
			<DragDropContext onDragEnd={onDragEnd}>
				<Droppable type="section" droppableId="course-builder">
					{(provided) => (
						<div {...provided.droppableProps} ref={provided.innerRef}>
							{data.sectionOrder.map((sectionId, index) => {
								const section = data.sections[sectionId];
								const contents = section.contentIds.map(
									(contentId) => data.contents[contentId]
								);
								return (
									<Section
										key={section.id}
										id={section.id}
										title={section.title}
										contents={contents}
										index={index}
									/>
								);
							})}
							{provided.placeholder}
						</div>
					)}
				</Droppable>
			</DragDropContext>
		</Container>
	);
};

export default SortableSections;
