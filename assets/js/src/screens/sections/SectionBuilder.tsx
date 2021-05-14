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
	const [builderData, setBuilderData] = useState({
		contents: {
			'130': {
				id: 130,
				name: 'Quiz Section 1',
				description: '',
				permalink: 'http://masteriyo.test/quiz/quiz-section-1/',
				type: 'quiz',
				menu_order: 0,
				parent_id: 126,
			},
			'129': {
				id: 129,
				name: 'lession 2 Section 1',
				description: '<p>Hello World</p>',
				permalink: 'http://masteriyo.test/lesson/lession-2-section-1-2/',
				type: 'lesson',
				menu_order: 0,
				parent_id: 125,
			},
			'127': {
				id: 127,
				name: 'lession Section 1',
				description: '<p>Hello World</p>',
				permalink: 'http://masteriyo.test/lesson/lession-section-1/',
				type: 'lesson',
				menu_order: 1,
				parent_id: 126,
			},
			'128': {
				id: 128,
				name: 'lession 2 Section 1',
				description: '<p>Hello World</p>',
				permalink: 'http://masteriyo.test/lesson/lession-2-section-1/',
				type: 'lesson',
				menu_order: 1,
				parent_id: 125,
			},
		},
		sections: {
			'125': {
				id: 125,
				name: 'Section 1',
				description: '',
				permalink: 'http://masteriyo.test/section/section-1/',
				type: 'section',
				menu_order: 0,
				parent_id: 124,
				contents: [129, 128],
			},
			'126': {
				id: 126,
				name: 'Section 2',
				description: '',
				permalink: 'http://masteriyo.test/section/section-2/',
				type: 'section',
				menu_order: 1,
				parent_id: 124,
				contents: [130, 127],
			},
		},
		section_order: [125, 126],
	});
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
				// setBuilderData(data);
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
		const { source, destination, draggableId, type } = result;

		if (!destination) {
			return;
		}
		if (
			destination.droppableId === source.droppableId &&
			destination.index === source.index
		) {
			return;
		}

		if (type === 'content') {
			console.log('this is content');
			const home = builderData.sections[source.droppableId];
			const foreign = builderData.sections[destination.droppableId];

			const homeTaskIds = Array.from(home.contents);
			homeTaskIds.splice(source.index, 1);
			const newHome = {
				...home,
				contents: homeTaskIds,
			};
			const foreignTaskIds = Array.from(foreign.contents);
			foreignTaskIds.splice(destination.index, 0, parseInt(draggableId));

			// const newForeign = {
			// 	...foreign,
			// 	contents: foreignTaskIds,
			// };

			// // console.log(newForeign);
			// const newSections = [
			// 		...builderData.sections,
			// 		source.droppableId: newHome,
			// 		destination.droppableId]: newForeign,
			// ];

			// console.log(newSections);

			// console.log('this is content');
			// const sourceContents = Array.from(
			// 	builderData.sections[source.droppableId].contents
			// );
			// const destinationContent = Array.from(
			// 	builderData.sections[destination.droppableId].contents
			// );

			// sourceContents.splice(source.index, 1);
			// destinationContent.splice(destination.index, 0, parseInt(draggableId));
			// console.log(sourceContents);
			// console.log(destinationContent);

			// const newSections = Array.from(builderData.sections);
			// const newSecti
			// const newSections = {
			// 	...builderData,
			// 	sections: [
			// 		source.droppableId: sourceContents,
			// 	destination.droppableId: destinationContent
			// 	]
			// 	,
			// }
		}
	};

	console.log(builderData?.sections);
	return (
		<DragDropContext onDragEnd={onDragEnd}>
			<Droppable droppableId="section" type="section">
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
									/>
								);
							})}

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
