import { Center } from '@chakra-ui/layout';
import { Box, Collapse } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { DragDropContext, Droppable, DropResult } from 'react-beautiful-dnd';
import AddNewButton from '../../components/common/AddNewButton';
import { reorder } from '../../utils/reorder';
import NewSection from './components/NewSection';
import Section from './components/Section';

interface Props {
	courseId: number | any;
	builderData: any;
	setBuilderData: any;
}

const SectionBuilder: React.FC<Props> = (props) => {
	const { courseId, builderData, setBuilderData } = props;
	const [isAddNewSection, setIsAddNewSection] = useState(false);
	const scrollRef = useRef<any>(null);

	const onDragEnd = (result: DropResult) => {
		const orderedData = reorder(result, builderData);
		setBuilderData(orderedData);
	};

	const onAddNewSectionPress = () => {
		setIsAddNewSection(true);
		setTimeout(() => {
			scrollRef.current.scrollIntoView({ behavior: 'smooth' });
		}, 600);
	};
	return (
		<DragDropContext onDragEnd={onDragEnd}>
			<Droppable droppableId="section" type="section">
				{(droppableProvided) => (
					<Box
						ref={droppableProvided.innerRef}
						{...droppableProvided.droppableProps}>
						{builderData.section_order.map((sectionId: any, index: any) => {
							const section = builderData.sections[sectionId];
							return (
								<Section
									key={section.id}
									id={section.id}
									index={index}
									name={section.name}
									description={section.description}
									courseId={courseId}
									contents={section.contents}
									contentsMap={builderData.contents}
								/>
							);
						})}

						<Collapse in={isAddNewSection} animateOpacity>
							<Box ref={scrollRef}>
								<NewSection
									courseId={courseId}
									onSave={() => setIsAddNewSection(false)}
									onCancel={() => setIsAddNewSection(false)}
								/>
							</Box>
						</Collapse>

						<Center mb="8">
							<AddNewButton onClick={onAddNewSectionPress}>
								{__('Add New Section', 'masteriyo')}
							</AddNewButton>
						</Center>

						{droppableProvided.placeholder}
					</Box>
				)}
			</Droppable>
		</DragDropContext>
	);
};

export default SectionBuilder;
