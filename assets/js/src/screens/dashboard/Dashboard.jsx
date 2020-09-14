import { Fragment, React, useState } from '@wordpress/element';
import arrayMove from 'array-move';
import {
	SortableContainer,
	SortableElement,
	SortableHandle,
} from 'react-sortable-hoc';
import MainLayout from '../layouts/MainLayout';
import MainToolbar from './../layouts/MainToolbar';

const Dashboard = () => {
	const DragHandle = SortableHandle(() => <span>::</span>);

	const SortableItem = SortableElement(({ value }) => (
		<li>
			<DragHandle />
			{value}
		</li>
	));

	const SortableSectionContainer = SortableContainer(({ children }) => {
		return <ul>{children}</ul>;
	});

	const [items, setItem] = useState([
		'Item 1',
		'Item 2',
		'Item 3',
		'Item 4',
		'Item 5',
		'Item 6',
	]);

	const onSortEnd = ({ oldIndex, newIndex }) => {
		setItem(arrayMove(items, oldIndex, newIndex));
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<SortableSectionContainer onSortEnd={onSortEnd} useDragHandle>
					{items.map((value, index) => (
						<SortableItem key={`item-${value}`} index={index} value={value} />
					))}
				</SortableSectionContainer>
			</MainLayout>
		</Fragment>
	);
};

export default Dashboard;
