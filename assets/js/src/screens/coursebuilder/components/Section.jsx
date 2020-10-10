import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import PropTypes from 'prop-types';
import defaultStyle, { BaseLine } from '../../../config/defaultStyle';
import DragHandle from './DragHandle';
import FlexRow from '../../../components/common/FlexRow';
import fontSize from '../../../config/fontSize';
import { Droppable, Draggable } from 'react-beautiful-dnd';
import Dropdown from 'rc-dropdown';
import OptionButton from '../../../components/common/OptionButton';
import DropdownOverlay from '../../../components/common/DropdownOverlay';
import Icon from '../../../components/common/Icon';
import { BiTrash } from 'react-icons/bi';
import Lesson from './content/lesson';

const Section = (props) => {
	const { id, title, contents, index } = props;

	return (
		<Draggable draggableId={id} index={index}>
			{(sectionProvided, snapshot) => (
				<SectionContainer
					{...sectionProvided.draggableProps}
					ref={sectionProvided.innerRef}
					isDragging={snapshot.isDragging}>
					<SectionHeader>
						<FlexRow>
							<DragHandle {...sectionProvided.dragHandleProps} />
							<SectionTitle>{title}</SectionTitle>
						</FlexRow>
						<FlexRow>
							<Dropdown
								trigger={'click'}
								placement={'bottomRight'}
								overlay={
									<DropdownOverlay>
										<ul>
											<li>
												<Icon icon={<BiTrash />} />
												Delete
											</li>
										</ul>
									</DropdownOverlay>
								}>
								<OptionButton />
							</Dropdown>
						</FlexRow>
					</SectionHeader>

					<Droppable droppableId={id} type="content">
						{(provided, snapshot) => (
							<ContentDroppableArea
								ref={provided.innerRef}
								{...provided.droppableProps}
								isDraggingOver={snapshot.isDraggingOver}>
								{contents.map(
									(content, index) =>
										content.type === 'lesson' && (
											<Lesson
												key={content.id}
												id={content.id}
												title={content.title}
												index={index}
											/>
										)
								)}
								{provided.placeholder}
							</ContentDroppableArea>
						)}
					</Droppable>
				</SectionContainer>
			)}
		</Draggable>
	);
};

Section.propTypes = {
	id: PropTypes.string,
	title: PropTypes.string,
	contents: PropTypes.array,
	index: PropTypes.number,
};

const SectionContainer = styled.div`
	background-color: ${colors.WHITE};
	border-radius: ${defaultStyle.borderRadius};
	padding: ${BaseLine * 4}px;
	margin-top: ${BaseLine * 6}px;
	box-shadow: ${(props) =>
		props.isDragging ? '0 0 15px rgba(0, 0, 0, 0.1)' : 'none'};
`;

const SectionHeader = styled.header`
	display: flex;
	margin-bottom: ${BaseLine * 4}px;
	justify-content: space-between;
`;

const SectionTitle = styled.h3`
	font-size: ${fontSize.EXTRA_LARGE};
	font-weight: 500;
	margin: 0;
`;

const ContentDroppableArea = styled.div`
	padding: ${BaseLine}px;
	background-color: ${(props) =>
		props.isDraggingOver ? colors.LIGHT_BLUEISH_GRAY : colors.WHITE};
	min-height: 100px;
`;

export default Section;
