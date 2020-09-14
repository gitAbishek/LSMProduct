import { React, useState } from '@wordpress/element';
import arrayMove from 'array-move';
import {
	SortableContainer,
	SortableElement,
	SortableHandle,
} from 'react-sortable-hoc';
import { BaseLine } from '../../../config/defaultStyle';
import colors from '../../../config/colors';
import defaultStyle from '../../../config/defaultStyle';
import styled from 'styled-components';
import Icon from '../../../components/common/Icon';
import { FaGripVertical } from 'react-icons/fa';
import fontSize from '../../../config/fontSize';

const DragHandle = SortableHandle(() => (
	<SortableIcon icon={<FaGripVertical />}></SortableIcon>
));

const SortableItem = SortableElement(({ value }) => (
	<StyledSortableItem>
		<DragHandle />
		<span>{value}</span>
	</StyledSortableItem>
));

const SortableSectionContainer = SortableContainer(({ children }) => {
	return <StyledSortable>{children}</StyledSortable>;
});

const SortableSection = () => {
	const [items, setItem] = useState([
		'Item 1',
		'Item 2',
		'Item 3',
		'Item 4',
		'Item 5',
		'Item 6',
	]);

	const onSortStart = () => {
		document.body.style.cursor = 'move';
	};
	const onSortEnd = ({ oldIndex, newIndex }) => {
		document.body.style.cursor = 'default';
		setItem(arrayMove(items, oldIndex, newIndex));
	};

	return (
		<SortableSectionContainer
			onSortStart={onSortStart}
			onSortEnd={onSortEnd}
			useDragHandle>
			{items.map((value, index) => (
				<SortableItem key={`item-${value}`} index={index} value={value} />
			))}
		</SortableSectionContainer>
	);
};

const StyledSortable = styled.ul`
	list-style-type: none;

	li {
		margin-bottom: ${BaseLine * 2}px;
	}
`;

const StyledSortableItem = styled.li`
	display: flex;
	align-items: center;
	list-style: none;
	padding: ${BaseLine * 2}px;
	border: 1px solid ${colors.BORDER};
	border-radius: ${defaultStyle.borderRadius};
`;

const SortableIcon = styled(Icon)`
	display: flex;
	margin-right: ${BaseLine * 2}px;
	color: ${colors.DISABLED};
	font-size: ${fontSize.HUGE};

	&:hover {
		cursor: move;
	}

	&:active {
		cursor: move;
	}
`;
export default SortableSection;
