import { React, useState } from '@wordpress/element';
import { BiAlignLeft, BiCopy, BiTrash } from 'react-icons/bi';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';

const SortableSection = () => {
	const [items, setItems] = useState([
		{
			id: '1122',
			label: 'This is label',
			datas: ['First Data', 'Second data'],
		},
		{
			id: '1111',
			label: 'Second Droppable',
			data: ['second data one', 'second data 2'],
		},
	]);

	const onDragEnd = () => {
		return;
	};

	return (
		<DragDropContext onDragEnd={onDragEnd}>
			<Droppable droppableId="uniqueID">
				{(dropProvided) => (
					<div
						ref={dropProvided.innerRef}
						{...dropProvided.draggableProps}
						{...dropProvided.dragHandleProps}>
						{items.map((item, index) => (
							<Draggable key={item.id} draggableId={item.id} index={index}>
								{(dragProvided) => (
									<span
										ref={dragProvided.innerRef}
										{...dragProvided.draggableProps}
										{...dragProvided.dragHandleProps}>
										{item.label}
									</span>
								)}
							</Draggable>
						))}

						{dropProvided.placeholder}
					</div>
				)}
			</Droppable>
		</DragDropContext>
	);
};

export default SortableSection;
