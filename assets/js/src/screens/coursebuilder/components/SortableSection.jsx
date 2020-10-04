import { React, useState } from '@wordpress/element';
import { BiAlignLeft, BiCopy, BiTrash } from 'react-icons/bi';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import Sections from './Sections';

const SortableSection = () => {
	const [list, setList] = useState({
		contents: {
			'content-1': { id: 'content-1', title: 'hello world' },
			'content-2': { id: 'content-2', title: 'section-2 content' },
		},
		sections: {
			'section-1': {
				id: 'section-1',
				title: 'Section 1',
				contentIds: ['content-1', 'content-2'],
			},
		},
		sectionOrder: ['section-1'],
	});

	// console.log(list);

	return (
		<div>
			<h2>nonono</h2>
			{list.sectionOrder.map((sectionId) => {
				const section = list.sections[sectionId];
				const contents = section.contentIds.map(
					(contentId) => list.contents[contentId]
				);
				return (
					<Sections key={section.id} section={section} contents={contents} />
				);
			})}
		</div>
	);
};

export default SortableSection;
