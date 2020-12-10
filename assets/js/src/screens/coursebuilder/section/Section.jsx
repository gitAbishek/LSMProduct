import { Draggable, Droppable } from 'react-beautiful-dnd';
import { Edit, Trash } from 'Icons';
import { React, useState } from '@wordpress/element';
import {
	SectionHeader,
	SectionTitle,
} from 'Components/common/GlobalComponents';
import defaultStyle, { BaseLine } from 'Config/defaultStyle';

import AddNewButton from 'Components/common/AddNewButton';
import Button from 'Components/common/Button';
import DragHandle from '../components/DragHandle';
import Dropdown from 'rc-dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FlexRow from 'Components/common/FlexRow';
import FormGroup from 'Components/common/FormGroup';
import Icon from 'Components/common/Icon';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import Lesson from '../lesson/Lesson';
import { NavLink } from 'react-router-dom';
import OptionButton from 'Components/common/OptionButton';
import PropTypes from 'prop-types';
import Quiz from '../quiz/Quiz';
import Textarea from 'Components/common/Textarea';
import colors from 'Config/colors';
import styled from 'styled-components';

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
												<Icon icon={<Edit />} />
												Edit
											</li>
											<li>
												<Icon icon={<Trash />} />
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
									<AddNewButton>
										<NavLink to={`/:${id}/add-new-lesson`}>
											Add New Content
										</NavLink>
									</AddNewButton>
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
