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
import Sortable from '../../../assets/icons/Sortable';
import arrayMove from 'array-move';
import fontSize from '../../../config/fontSize';
import Icon from '../../../components/common/Icon';
import Tooltip from 'rc-tooltip';
import Flex from '../../../components/common/Flex';

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
			<Icon icon={<BiAlignLeft />}></Icon>
			<span>{value}</span>
		</FlexRow>

		<FlexRow>
			<Tooltip placement="topRight" overlay="Duplicate">
				<Icon icon={<BiCopy />}></Icon>
			</Tooltip>
			<Tooltip placement="topRight" overlay="Delete">
				<Icon icon={<BiTrash />}></Icon>
			</Tooltip>
		</FlexRow>
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

const StyledSortable = styled.ul`
	margin: 0;
	padding: 0;
	list-style-type: none;

	li {
		margin-bottom: ${BaseLine * 1.5}px;
	}
`;

const SortablesectionItem = styled(Flex)`
	background-color: ${colors.WHITE};
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
