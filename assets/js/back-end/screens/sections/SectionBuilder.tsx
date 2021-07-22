import { Center } from '@chakra-ui/layout';
import { Box } from '@chakra-ui/react';
import { Spinner } from '@chakra-ui/spinner';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { DragDropContext, Droppable, DropResult } from 'react-beautiful-dnd';
import { useMutation, useQueryClient } from 'react-query';
import AddNewButton from '../../components/common/AddNewButton';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { reorder } from '../../utils/reorder';
import Section from './components/Section';

interface Props {
	courseId: number | any;
	builderData: any;
	setBuilderData: any;
}

const SectionBuilder: React.FC<Props> = (props) => {
	const { courseId, builderData, setBuilderData } = props;
	const queryClient = useQueryClient();
	const sectionAPI = new API(urls.sections);

	const addSection = useMutation((data: any) => sectionAPI.store(data), {
		onSuccess: () => {
			queryClient.invalidateQueries(`builder${courseId}`);
		},
	});

	const onAddNewSectionPress = () => {
		addSection.mutate({
			parent_id: courseId,
			course_id: courseId,
			name: 'New Section',
		});
	};

	const onDragEnd = (result: DropResult) => {
		const orderedData = reorder(result, builderData);
		setBuilderData(orderedData);
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

						{addSection.isLoading && (
							<Center minH="24">
								<Spinner />
							</Center>
						)}

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
