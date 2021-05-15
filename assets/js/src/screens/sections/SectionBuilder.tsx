import { Center, Stack } from '@chakra-ui/layout';
import { Spinner } from '@chakra-ui/spinner';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useState } from 'react';
import { DragDropContext, DropResult, Droppable } from 'react-beautiful-dnd';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { reorder } from '../../utils/reorder';
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
				setTotalSectionsLength(Object.keys(data.sections).length);
			},
			refetchOnWindowFocus: false,
			refetchIntervalInBackground: false,
			refetchOnReconnect: false,
		}
	);

	const updateBuilder = useMutation(
		(data: any) => builderAPI.update(courseId, data),
		{
			onSuccess: (data) => {
				console.log(data);
				queryClient.invalidateQueries('builderSections');
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
			menu_order: totalSectionsLength + 1,
		});
	};

	console.log(totalSectionsLength);

	const onDragEnd = (result: DropResult) => {
		const orderedData = reorder(result, builderData);

		if (orderedData) {
			setBuilderData(orderedData);
		}
	};

	return (
		<DragDropContext onDragEnd={onDragEnd}>
			<Droppable droppableId="section" type="section">
				{(droppableProvided) => (
					<Stack
						direction="column"
						spacing="8"
						ref={droppableProvided.innerRef}
						{...droppableProvided.droppableProps}>
						{(courseQuery.isLoading ||
							builderQuery.isLoading ||
							!builderData) && (
							<Center minH="xs">
								<Spinner />
							</Center>
						)}

						{builderData &&
							builderQuery.isSuccess &&
							builderData.section_order.map((sectionId: any, index: any) => {
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
						{courseQuery.isSuccess && builderQuery.isSuccess && builderData && (
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
