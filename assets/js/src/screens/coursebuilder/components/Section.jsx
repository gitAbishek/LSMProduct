import { React, useState } from '@wordpress/element';
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
import { BiEdit, BiTrash } from 'react-icons/bi';
import Lesson from './content/lesson';
import Quiz from './content/quiz';
import AddNewButton from '../../../components/common/AddNewButton';
import FormGroup from '../../../components/common/FormGroup';
import Label from '../../../components/common/Label';
import Input from '../../../components/common/Input';
import Textarea from '../../../components/common/Textarea';
import Button from '../../../components/common/Button';

const Section = (props) => {
	const { id, title, contents, index, editing } = props;

	const [mode, setMode] = useState(editing ? 'editing' : 'normal');

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
								animation={'slide-up'}
								overlay={
									<DropdownOverlay>
										<ul>
											<li onClick={() => setMode('editing')}>
												<Icon icon={<BiEdit />} />
												Edit
											</li>
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
					{mode === 'editing' && (
						<>
							<EditSection>
								<form action="">
									<FormGroup>
										<Label htmlFor="">Section Name</Label>
										<Input placeholder="Your Section Name"></Input>
									</FormGroup>
									<FormGroup>
										<Label htmlFor="">Section Description</Label>
										<Textarea rows="4" placeholder="short summary" />
									</FormGroup>
								</form>
							</EditSection>

							<SectionFooter>
								<FlexRow>
									<Button primary onClick={() => setMode('normal')}>
										Save
									</Button>
									<Button style={{ marginLeft: BaseLine * 2 }}>Cancel</Button>
								</FlexRow>
							</SectionFooter>
						</>
					)}

					{mode === 'normal' && (
						<Droppable droppableId={id} type="content">
							{(provided, snapshot) => (
								<ContentDroppableArea
									ref={provided.innerRef}
									{...provided.droppableProps}
									isDraggingOver={snapshot.isDraggingOver}>
									{contents.map(
										(content, index) =>
											(content.type === 'lesson' && (
												<Lesson
													key={content.id}
													id={content.id}
													title={content.title}
													index={index}
												/>
											)) ||
											(content.type === 'quiz' && (
												<Quiz
													key={content.id}
													id={content.id}
													title={content.title}
													index={index}
												/>
											))
									)}
									<AddNewButton>Add New Content</AddNewButton>
									{provided.placeholder}
								</ContentDroppableArea>
							)}
						</Droppable>
					)}
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
	editing: PropTypes.bool,
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
	margin-bottom: ${BaseLine * 3}px;
	justify-content: space-between;
`;

const SectionTitle = styled.h3`
	font-size: ${fontSize.EXTRA_LARGE};
	font-weight: 500;
	margin: 0;
`;

const ContentDroppableArea = styled.div`
	background-color: ${(props) =>
		props.isDraggingOver ? colors.LIGHT_BLUEISH_GRAY : colors.WHITE};
	min-height: ${BaseLine * 4}px;
`;

const EditSection = styled.div`
	margin-top: ${BaseLine * 4}px;
`;

const SectionFooter = styled.footer`
	margin-top: ${BaseLine * 5}px;
	padding-top: ${BaseLine * 4}px;
	border-top: 1px solid ${colors.BORDER};
`;

export default Section;
