import { Center, Stack } from '@chakra-ui/layout';
import { Box, Container, useToast } from '@chakra-ui/react';
import { Spinner } from '@chakra-ui/spinner';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import HeaderBuilder from 'Components/layout/HeaderBuilder';
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
	const toast = useToast();

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
				data.sections &&
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
			onSuccess: () => {
				toast({
					title: __('Builder Updated', 'masteriyo'),
					description: __('builder is updated.', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
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

	const onDragEnd = (result: DropResult) => {
		const orderedData = reorder(result, builderData);
		setBuilderData(orderedData);
	};

	const onSaveBtnPress = () => {
		updateBuilder.mutate(builderData);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<HeaderBuilder
				courseId={courseId}
				previewUrl={courseQuery?.data?.preview_permalink}
				onSave={onSaveBtnPress}
				isSaveLoading={updateBuilder.isLoading}
			/>
			<Container maxW="container.xl">
				<DragDropContext onDragEnd={onDragEnd}>
					<Droppable droppableId="section" type="section">
						{(droppableProvided) => (
							<Box
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
									builderData.section_order.map(
										(sectionId: any, index: any) => {
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
										}
									)}

								{addSection.isLoading && (
									<Center minH="24">
										<Spinner />
									</Center>
								)}
								{courseQuery.isSuccess &&
									builderQuery.isSuccess &&
									builderData && (
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
			</Container>
		</Stack>
	);
};

export default SectionBuilder;
