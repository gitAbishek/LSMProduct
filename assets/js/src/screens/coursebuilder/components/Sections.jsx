import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import Content from './Content';
import PropTypes from 'prop-types';
import defaultStyle, { BaseLine } from '../../../config/defaultStyle';
import DragHandle from './DragHandle';
import FlexRow from '../../../components/common/FlexRow';
import fontSize from '../../../config/fontSize';
import { Droppable } from 'react-beautiful-dnd';

const Sections = (props) => {
	const { section, contents } = props;

	return (
		<Container>
			<SectionHeader>
				<FlexRow>
					<DragHandle />
					<SectionTitle>{section.title}</SectionTitle>
				</FlexRow>
			</SectionHeader>

			<Droppable droppableId={section.id}>
				{(provided, snapshot) => (
					<DroppableArea
						ref={provided.innerRef}
						{...provided.droppableProps}
						isDraggingOver={snapshot.isDraggingOver}>
						{contents.map((content, index) => (
							<Content key={content.id} content={content} index={index} />
						))}
						{provided.placeholder}
					</DroppableArea>
				)}
			</Droppable>
		</Container>
	);
};

Sections.propTypes = {
	contents: PropTypes.array,
	section: PropTypes.object,
};

const Container = styled.div`
	background-color: ${colors.WHITE};
	border-radius: ${defaultStyle.borderRadius};
	padding: ${BaseLine * 4}px;
	margin-top: ${BaseLine * 6}px;
`;

const SectionHeader = styled.header`
	display: flex;
	margin-bottom: ${BaseLine * 4}px;
`;

const SectionTitle = styled.h3`
	font-size: ${fontSize.EXTRA_LARGE};
	font-weight: 500;
	margin: 0;
`;

const DroppableArea = styled.div`
	padding: ${BaseLine}px;
	background-color: ${(props) =>
		props.isDraggingOver ? colors.LIGHT_BLUEISH_GRAY : colors.WHITE};
`;
export default Sections;
