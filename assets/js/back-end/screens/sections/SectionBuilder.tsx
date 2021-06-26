import { Center } from '@chakra-ui/layout';
import { Box } from '@chakra-ui/react';
import { Spinner } from '@chakra-ui/spinner';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useState } from 'react';
import { DragDropContext, Droppable, DropResult } from 'react-beautiful-dnd';
import { useMutation, useQuery, useQueryClient } from 'react-query';
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
	const builderAPI = new API(urls.builder);

	const [totalSectionsLength, setTotalSectionsLength] = useState<number>(0);

	const builderQuery = useQuery(
		[`builder${courseId}`, courseId],
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
			menu_order: totalSectionsLength + 1,
		});
	};

	const onDragEnd = (result: DropResult) => {
		const orderedData = reorder(result, builderData);
		setBuilderData(orderedData);
	};

	if (builderQuery.isLoading || !builderData) {
		return <FullScreenLoader />;
	}

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
