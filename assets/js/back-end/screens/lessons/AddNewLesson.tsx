import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	Flex,
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import BackToBuilder from '../../components/common/BackToBuilder';
import Header from '../../components/common/Header';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import useCourse from '../../hooks/useCourse';
import { LessonSchema, SectionSchema } from '../../schemas';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { deepClean, deepMerge } from '../../utils/utils';
import FeaturedImage from '../courses/components/FeaturedImage';
import Description from './components/Description';
import Name from './components/Name';
import VideoSource from './components/VideoSource';

interface AddLessonFormData extends LessonSchema {
	featuredImage?: number;
}

const AddNewLesson: React.FC = () => {
	const { sectionId, courseId }: any = useParams();
	const toast = useToast();
	const queryClient = useQueryClient();
	const methods = useForm();
	const { draftCourse, publishCourse } = useCourse();
	const history = useHistory();
	const lessonAPI = new API(urls.lessons);
	const sectionsAPI = new API(urls.sections);
	const courseAPI = new API(urls.courses);

	const courseQuery = useQuery<CourseDataMap>(
		[`course${courseId}`, courseId],
		() => courseAPI.get(courseId)
	);
	// checks whether section exist or not
	const sectionQuery = useQuery<SectionSchema>(
		[`section${sectionId}`, sectionId],
		() => sectionsAPI.get(sectionId)
	);

	// adds lesson on the database
	const addLesson = useMutation((data: LessonSchema) => lessonAPI.store(data));

	const onSubmit = (data: AddLessonFormData, status?: 'publish' | 'draft') => {
		const newData = {
			course_id: courseId,
			parent_id: sectionId,
			featured_image: data.featuredImage,
		};
		status === 'draft' && draftCourse.mutate(courseId);
		status === 'publish' && publishCourse.mutate(courseId);

		addLesson.mutate(deepMerge(deepClean(data), newData), {
			onSuccess: (data: LessonSchema) => {
				toast({
					title: data.name + __(' has been added.', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`course${data.id}`);
				history.push({
					pathname: routes.courses.edit.replace(':courseId', courseId),
					search: '?page=builder&view=' + sectionId,
				});
			},
		});
	};
	const isPublished = () => {
		if (courseQuery.data?.status === 'publish') {
			return true;
		} else {
			return false;
		}
	};

	const isDrafted = () => {
		if (courseQuery.data?.status === 'draft') {
			return true;
		} else {
			return false;
		}
	};

	if (
		sectionQuery.isSuccess &&
		courseQuery.isSuccess &&
		sectionQuery.data.course_id == courseId
	) {
		return (
			<Stack direction="column" spacing="8" alignItems="center">
				<Header
					showCourseName
					showLinks
					showPreview
					course={{
						name: courseQuery.data.name,
						id: courseQuery.data.id,
						previewUrl: courseQuery.data.permalink,
					}}
					secondBtn={{
						label: isDrafted()
							? __('Save To Draft', 'masteriyo')
							: __('Switch To Draft', 'masteriyo'),
						action: methods.handleSubmit((data: AddLessonFormData) =>
							onSubmit(data, 'draft')
						),
						isLoading: draftCourse.isLoading,
					}}
					thirdBtn={{
						label: isPublished()
							? __('Update', 'masteriyo')
							: __('Publish', 'masteriyo'),
						action: methods.handleSubmit((data: AddLessonFormData) =>
							onSubmit(data, 'publish')
						),
						isLoading: publishCourse.isLoading,
					}}
				/>
				<Container maxW="container.xl">
					<Stack direction="column" spacing="6">
						<BackToBuilder />
						<FormProvider {...methods}>
							<Box bg="white" p="10" shadow="box">
								<Stack direction="column" spacing="8">
									<Flex aling="center" justify="space-between">
										<Heading as="h1" fontSize="x-large">
											{__('Add New Lesson', 'masteriyo')}
										</Heading>
										<Menu placement="bottom-end">
											<MenuButton
												as={IconButton}
												icon={<BiDotsVerticalRounded />}
												variant="outline"
												rounded="sm"
												fontSize="large"
											/>
											<MenuList>
												<MenuItem icon={<BiEdit />}>
													{__('Edit', 'masteriyo')}
												</MenuItem>
												<MenuItem icon={<BiTrash />}>
													{__('Delete', 'masteriyo')}
												</MenuItem>
											</MenuList>
										</Menu>
									</Flex>

									<form
										onSubmit={methods.handleSubmit((data: AddLessonFormData) =>
											onSubmit(data)
										)}>
										<Stack direction="column" spacing="6">
											<Name />
											<Description />
											<FeaturedImage size="masteriyo_single" />
											<VideoSource />

											{/* <Box py="3">
												<Divider />
											</Box>

											<Attachment /> */}

											<Box py="3">
												<Divider />
											</Box>

											<ButtonGroup>
												<Button
													colorScheme="blue"
													type="submit"
													isLoading={addLesson.isLoading}>
													{__('Add New Lesson', 'masteriyo')}
												</Button>
												<Button
													variant="outline"
													onClick={() =>
														history.push({
															pathname: routes.courses.edit.replace(
																':courseId',
																courseId
															),
															search: '?page=builder&view=' + sectionId,
														})
													}>
													{__('Cancel', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</Stack>
							</Box>
						</FormProvider>
					</Stack>
				</Container>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default AddNewLesson;
