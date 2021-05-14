import { Center, Stack } from '@chakra-ui/layout';
import { Spinner } from '@chakra-ui/spinner';
import { __ } from '@wordpress/i18n';
import arrayMove from 'array-move';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useState } from 'react';
import {
	DragDropContext,
	Draggable,
	DropResult,
	Droppable,
} from 'react-beautiful-dnd';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Section from './components/Section';

const SectionBuilder = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();
	const history = useHistory();
	const courseAPI = new API(urls.courses);
	const sectionAPI = new API(urls.sections);
	const builderAPI = new API(urls.builder);
	const [builderData, setBuilderData] = useState<any>(null);
	const [totalSectionsLength, setTotalSectionsLength] = useState<number>(0);
	const courseQuery = useQuery(
		['builderCourse', courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const builderQuery = useQuery(
		['builderSections'],
		() => builderAPI.get(courseId),
		{
			onSuccess: (data) => {
				setBuilderData(data);
			},
		}
	);

	const addSection = useMutation((data: any) => sectionAPI.store(data), {
		onSuccess: () => {
			queryClient.invalidateQueries('builderSections');
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
		const { source, destination, draggableId } = result;

		if (!destination) {
			return;
		}
		if (
			destination.droppableId === source.droppableId &&
			destination.index === source.index
		) {
			return;
		}

		const newSections = arrayMove(
			builderData.sections,
			source.index,
			destination.index
		);

		setBuilderData({
			...builderData,
			sections: newSections,
		});
	};

	return (
		<DragDropContext onDragEnd={onDragEnd}>
			<Droppable droppableId="section">
				{(droppableProvided) => (
					<Stack
						direction="column"
						spacing="8"
						ref={droppableProvided.innerRef}
						{...droppableProvided.droppableProps}>
						{(courseQuery.isLoading || builderQuery.isLoading) && (
							<Center minH="xs">
								<Spinner />
							</Center>
						)}

						{builderQuery.isSuccess &&
							builderData.sections.map((section: any, index: any) => (
								<Section
									key={section.id}
									id={section.id}
									index={index}
									name={section.name}
									description={section.description}
									courseId={courseId}
								/>
							))}

						{addSection.isLoading && (
							<Center minH="24">
								<Spinner />
							</Center>
						)}
						{courseQuery.isSuccess && builderQuery.isSuccess && (
							<Center>
								<AddNewButton onClick={onAddNewSectionPress}>
									{__('Add New Section', 'masteriyo')}
								</AddNewButton>
							</Center>
						)}
						{droppableProvided.placeholder}
					</Stack>
				)}
			</Droppable>
		</DragDropContext>
	);
};

export default SectionBuilder;
