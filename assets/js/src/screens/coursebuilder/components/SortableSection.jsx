import { React, useState } from '@wordpress/element';
import { DragDropContext } from 'react-beautiful-dnd';
import Sections from './Sections';
import Container from '../../../components/common/Container';

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
		const { destination, source, draggableId } = result;
		if (!destination) {
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
				{list.sectionOrder.map((sectionId) => {
					const section = list.sections[sectionId];
					const contents = section.contentIds.map(
						(contentId) => list.contents[contentId]
					);
					return (
						<Sections key={section.id} section={section} contents={contents} />
					);
				})}
			</DragDropContext>
		</Container>
	);
};

export default SortableSection;
