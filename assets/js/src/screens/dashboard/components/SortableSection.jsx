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
import { BiImageAdd, BiTrash, BiCopy, BiAlignLeft } from 'react-icons/bi';
import fontSize from '../../../config/fontSize';
import FlexRow from '../../../components/common/FlexRow';
import Flex from '../../../components/common/Flex';
import Sortable from '../../../assets/icons/Sortable';
import Tooltip from '../../../components/common/Tooltip';

const DragHandle = SortableHandle(() => (
	<SortableIcon icon={<Sortable />}></SortableIcon>
));

const SortableItem = SortableElement(({ value }) => (
	<StyledSortableItem>
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

const StyledSortableItem = styled.li`
	display: flex;
	align-items: center;
	list-style: none;
	padding: ${BaseLine * 1.8}px;
	border: 1px solid ${colors.BORDER};
	border-radius: ${defaultStyle.borderRadius};
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

const Actions = styled(FlexRow)`
	margin-left: auto;

	i {
		font-size: ${fontSize.HUGE};
		margin-left: ${BaseLine}px;
		color: ${colors.LIGHT_TEXT};
	}
`;

const Type = styled(Flex)`
	margin-right: ${BaseLine * 2}px;
	i {
		color: ${colors.TEXT};
		font-size: ${fontSize.HUGE};
	}
`;
export default SortableSection;
