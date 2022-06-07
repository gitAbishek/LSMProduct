import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
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
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import BackToBuilder from '../../components/common/BackToBuilder';
import Header from '../../components/common/Header';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import useCourse from '../../hooks/useCourse';
import { LessonSchema } from '../../schemas';
import LessonSkeleton from '../../skeleton/LessonSkeleton';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { deepClean } from '../../utils/utils';
import FeaturedImage from '../courses/components/FeaturedImage';
import Description from './components/Description';
import Name from './components/Name';
import VideoSource from './components/VideoSource';

interface EditLessonFormData extends LessonSchema {
	featuredImage?: number;
}

const EditLesson = () => {
	const { lessonId, courseId }: any = useParams();
	const { draftCourse, publishCourse } = useCourse();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const lessonAPI = new API(urls.lessons);
	const courseAPI = new API(urls.courses);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();

	const courseQuery = useQuery<CourseDataMap>(
		[`course${courseId}`, courseId],
		() => courseAPI.get(courseId)
	);

	const lessonQuery = useQuery<LessonSchema>(
		[`section${lessonId}`, lessonId],
		() => lessonAPI.get(lessonId)
	);

	const updateLesson = useMutation(
		(data: LessonSchema) => lessonAPI.update(lessonId, data),
		{
			onSuccess: () => {
				queryClient.invalidateQueries(`section${lessonId}`);
				toast({
					title: __('Lesson Updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});

				history.push({
					pathname: routes.courses.edit.replace(':courseId', courseId),
					search: '?page=builder',
				});
			},
		}
	);

	const deleteLesson = useMutation(
		(lessonId: number) => lessonAPI.delete(lessonId),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Lesson Deleted.', 'masteriyo'),
					isClosable: true,
					status: 'error',
				});
				history.push({
					pathname: routes.courses.edit.replace(':courseId', data.course_id),
					search: '?page=builder',
				});
			},
		}
	);

	const onSubmit = (data: EditLessonFormData, status?: 'draft' | 'publish') => {
		status === 'draft' && draftCourse.mutate(courseId);
		status === 'publish' && publishCourse.mutate(courseId);
		updateLesson.mutate(
			deepClean({ ...data, featured_image: data.featuredImage })
		);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteLesson.mutate(lessonId);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
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
		lessonQuery.isSuccess &&
		courseQuery.isSuccess &&
		lessonQuery.data.course_id == courseId
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
						action: methods.handleSubmit((data: EditLessonFormData) =>
							onSubmit(data, 'draft')
						),
						isLoading: draftCourse.isLoading,
					}}
					thirdBtn={{
						label: isPublished()
							? __('Update', 'masteriyo')
							: __('Publish', 'masteriyo'),
						action: methods.handleSubmit((data: EditLessonFormData) =>
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
											{__('Edit Lesson', 'masteriyo')}
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
												<MenuItem icon={<BiTrash />} onClick={onDeletePress}>
													{__('Delete', 'masteriyo')}
												</MenuItem>
											</MenuList>
										</Menu>
									</Flex>

									<form
										onSubmit={methods.handleSubmit((data: EditLessonFormData) =>
											onSubmit(data)
										)}>
										<Stack direction="column" spacing="6">
											<Name defaultValue={lessonQuery.data.name} />
											<Description
												defaultValue={lessonQuery.data.description}
											/>
											<FeaturedImage
												defaultValue={lessonQuery.data.featured_image}
												size="masteriyo_single"
											/>
											<VideoSource
												defaultSource={lessonQuery.data.video_source}
												defaultSourceUrl={lessonQuery.data.video_source_url}
												defaultSourceID={lessonQuery.data.video_source_id}
											/>

											<Box py="3">
												<Divider />
											</Box>

											<ButtonGroup>
												<Button
													colorScheme="blue"
													type="submit"
													isLoading={updateLesson.isLoading}>
													{__('Update Lesson', 'masteriyo')}
												</Button>
												<Button
													variant="outline"
													onClick={() =>
														history.push({
															pathname: routes.courses.edit.replace(
																':courseId',
																courseId
															),
															search: '?page=builder',
														})
													}>
													{__('Cancel', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</Stack>
									</form>
								</Stack>
							</Box>
							<AlertDialog
								isOpen={isDeleteModalOpen}
								onClose={onDeleteModalClose}
								isCentered
								leastDestructiveRef={cancelRef}>
								<AlertDialogOverlay>
									<AlertDialogContent>
										<AlertDialogHeader>
											{__('Delete Lesson', 'masteriyo')} {name}
										</AlertDialogHeader>

										<AlertDialogBody>
											{__(
												'Are you sure? You can’t restore after deleting.',
												'masteriyo'
											)}
										</AlertDialogBody>
										<AlertDialogFooter>
											<ButtonGroup>
												<Button
													ref={cancelRef}
													onClick={onDeleteModalClose}
													variant="outline">
													{__('Cancel', 'masteriyo')}
												</Button>
												<Button
													colorScheme="red"
													onClick={onDeleteConfirm}
													isLoading={deleteLesson.isLoading}>
													{__('Delete', 'masteriyo')}
												</Button>
											</ButtonGroup>
										</AlertDialogFooter>
									</AlertDialogContent>
								</AlertDialogOverlay>
							</AlertDialog>
						</FormProvider>
					</Stack>
				</Container>
			</Stack>
		);
	}

	return <LessonSkeleton />;
};

export default EditLesson;
