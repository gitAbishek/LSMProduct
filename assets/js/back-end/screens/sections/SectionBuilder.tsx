import { Center } from '@chakra-ui/layout';
import {
	Box,
	Button,
	ButtonGroup,
	Collapse,
	Heading,
	Link,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import queryString from 'query-string';
import React, { useEffect, useRef, useState } from 'react';
import { DragDropContext, Droppable, DropResult } from 'react-beautiful-dnd';
import { useLocation } from 'react-router-dom';
import AddNewButton from '../../components/common/AddNewButton';
import { reorder } from '../../utils/reorder';
import { isEmpty } from '../../utils/utils';
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
	const { search } = useLocation();
	const { view } = queryString.parse(search);

	useEffect(() => {
		document
			.getElementById(`add-new-section-content-${view}`)
			?.scrollIntoView({ behavior: 'smooth', block: 'center' });
	}, [view]);

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
						{builderData?.section_order?.map((sectionId: any, index: any) => {
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

						{isEmpty(builderData.section_order)
							? !isAddNewSection && (
									<Box
										py="16"
										px="8"
										bg="white"
										shadow="box"
										mb="8"
										textAlign="center">
										<Stack direction="column">
											<Heading fontSize="2xl">
												{__('Get Started', 'Masteriyo')}
											</Heading>
											<Stack direction="column" spacing="6">
												<Text color="gray.500" fontSize="xs">
													{__(
														'Add new section to add your content.',
														'masteriyo'
													)}
												</Text>
												<ButtonGroup justifyContent="center">
													<Button
														colorScheme="primary"
														onClick={onAddNewSectionPress}>
														{__('Add New Section', 'masteriyo')}
													</Button>
												</ButtonGroup>
												<Text color="gray.500" fontSize="xs">
													{__(
														'Not sure how to get started? Learn more in our',
														'masteriyo'
													)}
													<Link
														isExternal
														href="https://docs.masteriyo.com/course-creation/complete-course-creation">
														<Text as="span" color="gray.800" fontSize="xs">
															{__(' Documentation', 'masteriyo')}
														</Text>
													</Link>
												</Text>
											</Stack>
										</Stack>
									</Box>
							  )
							: !isAddNewSection && (
									<Center mb="8">
										<AddNewButton onClick={onAddNewSectionPress}>
											{__('Add New Section', 'masteriyo')}
										</AddNewButton>
									</Center>
							  )}
						{droppableProvided.placeholder}
					</Box>
				)}
			</Droppable>
		</DragDropContext>
	);
};

export default SectionBuilder;
