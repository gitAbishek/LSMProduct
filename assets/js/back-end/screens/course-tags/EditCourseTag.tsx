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
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import DescriptionInput from './components/DescriptionInput';
import Header from './components/Header';
import NameInput from './components/NameInput';
import SlugInput from './components/SlugInput';

const EditCourseTag = () => {
	const { tagId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const tagAPI = new API(urls.tags);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();

	const tagQuery = useQuery(
		[`courseTag${tagId}`, tagId],
		() => tagAPI.get(tagId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const updateTag = useMutation((data: object) => tagAPI.update(tagId, data), {
		onSuccess: (data: any) => {
			toast({
				title: __('Tag Updated Successfully', 'masteriyo'),
				description: data.name + __(' has been updated successfully.'),
				isClosable: true,
				status: 'success',
			});
			history.push(routes.course_tags.list);
			queryClient.invalidateQueries('courseTagsList');
		},
		onError: (error: any) => {
			toast({
				title: __('Failed to update tag', 'masteriyo'),
				description: `${error.response?.data?.message}`,
				isClosable: true,
				status: 'error',
			});
		},
	});

	const deleteTag = useMutation(
		(tagId: number) => tagAPI.delete(tagId, { force: true }),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Tag Deleted Successfully', 'masteriyo'),
					description: data.name + __(' has been deleted successfully.'),
					isClosable: true,
					status: 'error',
				});
				history.push(routes.course_tags.list);
				queryClient.invalidateQueries('courseTagsList');
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to delete tag', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: object) => {
		updateTag.mutate(data);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteTag.mutate(tagId);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	if (tagQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<Box bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="8">
							<Flex aling="center" justify="space-between">
								<Heading as="h1" fontSize="x-large">
									{__('Edit Tag', 'masteriyo')}
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
									<NameInput defaultValue={tagQuery.data.name} />
									<SlugInput defaultValue={tagQuery.data.slug} />
									<DescriptionInput defaultValue={tagQuery.data.description} />

									<Box py="3">
										<Divider />
									</Box>

									<ButtonGroup>
										<Button
											colorScheme="blue"
											type="submit"
											isLoading={updateTag.isLoading}>
											{__('Update', 'masteriyo')}
										</Button>
										<Button
											variant="outline"
											onClick={() => history.push(routes.course_tags.list)}>
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
									{__('Delete Tag')} {name}
								</AlertDialogHeader>

								<AlertDialogBody>
									{__("Are you sure? You can't restore this tag", 'masteriyo')}
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
											isLoading={deleteTag.isLoading}>
											{__('Delete', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</AlertDialogFooter>
							</AlertDialogContent>
						</AlertDialogOverlay>
					</AlertDialog>
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default EditCourseTag;
