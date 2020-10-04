import { React, useState } from '@wordpress/element';
import { DragDropContext, Droppable } from 'react-beautiful-dnd';
import Sections from './Sections';
import Container from '../../../components/common/Container';
import styled from 'styled-components';
import colors from '../../../config/colors';

const SortableSection = () => {
	const [list, setList] = useState({
		contents: {
			'content-1': { id: 'content-1', title: 'hello world' },
			'content-2': { id: 'content-2', title: 'Content for section' },
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
	});

	const onDragEnd = (result) => {
		const { destination, source, draggableId, type } = result;
		if (!destination) {
			return;
		}

		// Reorder section move
		if (type === 'section') {
			console.log('section is moving');
			const newSectionOrder = Array.from(list.sectionOrder);
			newSectionOrder.splice(source.index, 1);
			newSectionOrder.splice(destination.index, 0, draggableId);
			setList({ ...list, sectionOrder: newSectionOrder });
			return;
		}

		const start = list.sections[source.droppableId];
		const finish = list.sections[destination.droppableId];

		if (start === finish) {
			const newContentIds = Array.from(start.contentIds);
			newContentIds.splice(source.index, 1);
			newContentIds.splice(destination.index, 0, draggableId);

			const newSection = {
				...start,
				contentIds: newContentIds,
			};

			setList({
				...list,
				sections: {
					...list.sections,
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

		setList({
			...list,
			sections: {
				...list.sections,
				[newStart.id]: newStart,
				[newFinish.id]: newFinish,
			},
		});
	};

	return (
		<Container>
			<DragDropContext onDragEnd={onDragEnd}>
				<Droppable type="section" droppableId="main-droppable-container">
					{(provided) => (
						<div {...provided.droppableProps} ref={provided.innerRef} i>
							{list.sectionOrder.map((sectionId, index) => {
								const section = list.sections[sectionId];
								const contents = section.contentIds.map(
									(contentId) => list.contents[contentId]
								);
								return (
									<Sections
										key={section.id}
										section={section}
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

export default SortableSection;
