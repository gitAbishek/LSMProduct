import {
	Container,
	Heading,
	Stack,
	TabPanel,
	TabPanels,
	Tabs,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import EditCourse from '../courses/EditCourse';
import SectionBuilder from '../sections/SectionBuilder';
import Header from './component/Header';

const Builder: React.FC = () => {
	const { courseId }: any = useParams();
	const history = useHistory();
	const queryClient = useQueryClient();
	const toast = useToast();
	const methods = useForm();
	const courseAPI = new API(urls.courses);
	const builderAPI = new API(urls.builder);

	const [builderData, setBuilderData] = useState<any>(null);
	const [courseData, setCourseData] = useState<CourseDataMap>();

	const tabPanelStyles = {
		px: '0',
	};

	const courseQuery = useQuery<CourseDataMap>(
		[`courses${courseId}`, courseId],
		() => courseAPI.get(courseId),
		{
			onSuccess: (data: CourseDataMap) => {
				setCourseData(data);
			},
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const updateBuilder = useMutation(
		(data: any) => builderAPI.update(courseId, data),
		{
			onSuccess: () => {
				queryClient.invalidateQueries(`builder${courseId}`);
			},
		}
	);

	const updateCourse = useMutation(
		(data: CourseDataMap) => courseAPI.update(courseId, data),
		{
			onSuccess: (data: CourseDataMap) => {
				toast({
					title: data.name + __(' is updated successfully.', 'masteriyo'),
					description: __('You can keep editing it', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`courses${data.id}`);
			},
		}
	);

	const onSave = () => {
		if (builderData) {
			updateBuilder.mutate(builderData);
		}
		if (courseData) {
			updateCourse.mutate(courseData);
		}
	};

	if (courseQuery.isLoading || !courseData) {
		return <FullScreenLoader />;
	}

	return (
		<FormProvider {...methods}>
			<Tabs>
				<Stack direction="column" spacing="10" align="center">
					<Header previewUrl={courseData?.preview_permalink} onSave={onSave} />
					<Container maxW="container.xl">
						<Stack direction="column" spacing="6">
							<Heading as="h1" fontSize="x-large">
								{__('Edit Course: ', 'masteriyo')} {courseQuery.data?.name}
							</Heading>
							<TabPanels>
								<TabPanel sx={tabPanelStyles}>
									<EditCourse courseData={courseData} />
								</TabPanel>
								<TabPanel sx={tabPanelStyles}>
									<SectionBuilder
										courseId={courseQuery.data?.id}
										builderData={builderData}
										setBuilderData={setBuilderData}
									/>
								</TabPanel>
							</TabPanels>
						</Stack>
					</Container>
				</Stack>
			</Tabs>
		</FormProvider>
	);
};

export default Builder;
