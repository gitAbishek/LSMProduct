import { Edit, Trash } from '../../../assets/icons';
import React, { useState } from 'react';
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
import Lesson from './Lesson';
import { NavLink } from 'react-router-dom';
import OptionButton from 'Components/common/OptionButton';
import Textarea from 'Components/common/Textarea';
import colors from 'Config/colors';
import { fetchLessons } from '../../../utils/api';
import styled from 'styled-components';
import { useQuery } from 'react-query';

interface Props {
	id: number;
	title: string;
	contents?: any;
	editing?: boolean;
	index: number;
	courseId: number;
}

const Section: React.FC<Props> = (props) => {
	const { id, title, contents, index, editing, courseId } = props;

	const [mode, setMode] = useState(editing ? 'editing' : 'normal');

	const { data: lessonData, isError: isLessonError } = useQuery(
		['lessons', courseId],
		() => fetchLessons(courseId),
		{
			enabled: true,
		}
	);

	return (
		<SectionContainer>
			<SectionHeader>
				<FlexRow>
					<DragHandle />
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
								<Textarea rows={4} placeholder="short summary" />
							</FormGroup>
						</form>
					</EditSection>

					<SectionFooter>
						<FlexRow>
							<Button appearance="primary" onClick={() => setMode('normal')}>
								Save
							</Button>
							<Button style={{ marginLeft: BaseLine * 2 }}>Cancel</Button>
						</FlexRow>
					</SectionFooter>
				</>
			)}

			<ContentDroppableArea>
				{lessonData?.map((content: any, index: number) => (
					<Lesson
						key={content.id}
						id={content.id}
						title={content.name}
						index={index}
					/>
				))}
				<AddNewButton>
					<NavLink to={`/courses/${courseId}/add-new-lesson`}>
						Add New Content
					</NavLink>
				</AddNewButton>
			</ContentDroppableArea>
		</SectionContainer>
	);
};

interface StyledProps {
	isDragging?: boolean;
	isDraggingOver?: boolean;
}

const SectionContainer = styled.div`
	background-color: ${colors.WHITE};
	border-radius: ${defaultStyle.borderRadius};
	padding: ${BaseLine * 4}px;
	margin-top: ${BaseLine * 6}px;
	box-shadow: ${(props: StyledProps) =>
		props.isDragging ? '0 0 15px rgba(0, 0, 0, 0.1)' : 'none'};
`;

const ContentDroppableArea = styled.div`
	background-color: ${(props: StyledProps) =>
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
