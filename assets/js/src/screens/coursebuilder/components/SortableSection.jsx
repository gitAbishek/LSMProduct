import { React, useState } from '@wordpress/element';
import { BiAlignLeft, BiCopy, BiTrash } from 'react-icons/bi';
import {
	SortableContainer,
	SortableElement,
	SortableHandle,
} from 'react-sortable-hoc';
import styled from 'styled-components';
import FlexRow from '../../../components/common/FlexRow';
import colors from '../../../config/colors';
import { BaseLine } from '../../../config/defaultStyle';

const DragHandle = SortableHandle(() => (
	<SortableIcon icon={<Sortable />}></SortableIcon>
));

const SortableSectionContainer = SortableContainer(({ children }) => {
	return <StyledSortable>{children}</StyledSortable>;
});

const SortableItem = SortableElement(({ value }) => (
	<SortablesectionItem>
		<FlexRow>
			<DragHandle />
			<Type>
				<Icon icon={<BiAlignLeft />}></Icon>
			</Type>
			<span>{value}</span>
		</FlexRow>

		<Actions>
			<Tooltip placement="topRight" overlay="Duplicate">
				<Icon icon={<BiCopy />}></Icon>
			</Tooltip>
			<Tooltip placement="topRight" overlay="Delete">
				<Icon icon={<BiTrash />}></Icon>
			</Tooltip>
		</Actions>
	</SortablesectionItem>
));

const SortableSection = () => {
	const [items, setItem] = useState([
		'Section 1',
		'Section 2',
		'Section 3',
		'Section 4',
		'Section 5',
		'Section 6',
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
			useDragHandle
			helperClass="sortable-dragging">
			{items.map((value, index) => (
				<SortableItem key={`item-${value}`} index={index} value={value} />
			))}
		</SortableSectionContainer>
	);
};

const SortableSectionItem = styled.li`
	padding: ${BaseLine * 6}px;
	background-color: ${colors.WHITE};
`;

const SortableSectionHeader = styled(FlexRow)`
	justify-content: space-between;
`;

const SortableIcon = styled(Icon)`
	margin-right: ${BaseLine * 2}px;
	color: ${colors.DISABLED};
	font-size: ${fontSize.EXTRA_LARGE};

	&:hover {
		cursor: move;
	}

	&:active {
		cursor: move;
	}
`;

export default SortableSection;
