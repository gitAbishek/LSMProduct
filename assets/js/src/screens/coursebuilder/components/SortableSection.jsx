import { React, useState } from '@wordpress/element';
import { DragDropContext } from 'react-beautiful-dnd';
import Sections from './Sections';
import Container from '../../../components/common/Container';

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

	const onDragEnd = (result) => {
		return;
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
