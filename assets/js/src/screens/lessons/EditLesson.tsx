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
	Center,
	Divider,
	Flex,
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Spinner,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';

const EditLesson: React.FC = () => {
	const { lessonId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const lessonAPI = new API(urls.lessons);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);

	const lessonQuery = useQuery(
		[`section${lessonId}`, lessonId],
		() => lessonAPI.get(lessonId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const updateLesson = useMutation(
		(data: object) => lessonAPI.update(lessonId, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Lesson Updated Successfully', 'masteriyo'),
					description: data.name + __(' has been updated successfully.'),
					isClosable: true,
					status: 'success',
				});
				history.push(routes.section.replace(':courseId', data.course_id));
			},
		}
	);

	const deleteLesson = useMutation(
		(lessonId: number) => lessonAPI.delete(lessonId),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Lesson Deleted Successfully', 'masteriyo'),
					description: data.name + __(' has been deleted successfully.'),
					isClosable: true,
					status: 'error',
				});
				history.push(routes.section.replace(':courseId', data.course_id));
			},
		}
	);

	const onSubmit = (data: object) => {
		updateLesson.mutate(data);
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

	return (
		<FormProvider {...methods}>
			<Box bg="white" p="10" shadow="box">
				{lessonQuery.isLoading && (
					<Center minH="xs">
						<Spinner />
					</Center>
				)}
				{lessonQuery.isSuccess && (
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

						<form onSubmit={methods.handleSubmit(onSubmit)}>
							<Stack direction="column" spacing="6">
								<Name defaultValue={lessonQuery.data.name} />
								<Description defaultValue={lessonQuery.data.description} />
								<FeaturedImage defaultValue={lessonQuery.data.featured_image} />

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
									<Button variant="outline" onClick={() => history.goBack()}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Stack>
						</form>
					</Stack>
				)}
			</Box>
			<AlertDialog
				isOpen={isDeleteModalOpen}
				onClose={onDeleteModalClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{__('Delete Lesson')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							Are you sure? You can't restore this section
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
	);
};

export default EditLesson;
